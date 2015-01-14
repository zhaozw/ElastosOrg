<?php

abstract class PhabricatorStorageManagementWorkflow
  extends PhabricatorManagementWorkflow {

  private $patches;
  private $api;

  public function setPatches(array $patches) {
    assert_instances_of($patches, 'PhabricatorStoragePatch');
    $this->patches = $patches;
    return $this;
  }

  public function getPatches() {
    return $this->patches;
  }

  final public function setAPI(PhabricatorStorageManagementAPI $api) {
    $this->api = $api;
    return $this;
  }

  final public function getAPI() {
    return $this->api;
  }

  private function loadSchemata() {
    $query = id(new PhabricatorConfigSchemaQuery())
      ->setAPI($this->getAPI());

    $actual = $query->loadActualSchema();
    $expect = $query->loadExpectedSchema();
    $comp = $query->buildComparisonSchema($expect, $actual);

    return array($comp, $expect, $actual);
  }

  protected function adjustSchemata($force, $unsafe, $dry_run) {
    $console = PhutilConsole::getConsole();

    $console->writeOut(
      "%s\n",
      pht('Verifying database schemata...'));

    list($adjustments, $errors) = $this->findAdjustments();
    $api = $this->getAPI();

    if (!$adjustments) {
      $console->writeOut(
        "%s\n",
        pht('Found no adjustments for schemata.'));

      return $this->printErrors($errors, 0);
    }

    if (!$force && !$api->isCharacterSetAvailable('utf8mb4')) {
      $message = pht(
        "You have an old version of MySQL (older than 5.5) which does not ".
        "support the utf8mb4 character set. If you apply adjustments now ".
        "and later update MySQL to 5.5 or newer, you'll need to apply ".
        "adjustments again (and they will take a long time).\n\n".
        "You can exit this workflow, update MySQL now, and then run this ".
        "workflow again. This is recommended, but may cause a lot of downtime ".
        "right now.\n\n".
        "You can exit this workflow, continue using Phabricator without ".
        "applying adjustments, update MySQL at a later date, and then run ".
        "this workflow again. This is also a good approach, and will let you ".
        "delay downtime until later.\n\n".
        "You can proceed with this workflow, and then optionally update ".
        "MySQL at a later date. After you do, you'll need to apply ".
        "adjustments again.\n\n".
        "For more information, see \"Managing Storage Adjustments\" in ".
        "the documentation.");

      $console->writeOut(
        "\n**<bg:yellow> %s </bg>**\n\n%s\n",
        pht('OLD MySQL VERSION'),
        phutil_console_wrap($message));

      $prompt = pht('Continue with old MySQL version?');
      if (!phutil_console_confirm($prompt, $default_no = true)) {
        return;
      }
    }

    $table = id(new PhutilConsoleTable())
      ->addColumn('database', array('title' => pht('Database')))
      ->addColumn('table', array('title' => pht('Table')))
      ->addColumn('name', array('title' => pht('Name')))
      ->addColumn('info', array('title' => pht('Issues')));

    foreach ($adjustments as $adjust) {
      $info = array();
      foreach ($adjust['issues'] as $issue) {
        $info[] = PhabricatorConfigStorageSchema::getIssueName($issue);
      }

      $table->addRow(array(
        'database' => $adjust['database'],
        'table' => idx($adjust, 'table'),
        'name' => idx($adjust, 'name'),
        'info' => implode(', ', $info),
      ));
    }

    $console->writeOut("\n\n");

    $table->draw();

    if ($dry_run) {
      $console->writeOut(
        "%s\n",
        pht('DRYRUN: Would apply adjustments.'));
      return 0;
    } else if (!$force) {
      $console->writeOut(
        "\n%s\n",
        pht(
          "Found %s issues(s) with schemata, detailed above.\n\n".
          "You can review issues in more detail from the web interface, ".
          "in Config > Database Status. To better understand the adjustment ".
          "workflow, see \"Managing Storage Adjustments\" in the ".
          "documentation.\n\n".
          "MySQL needs to copy table data to make some adjustments, so these ".
          "migrations may take some time.",
          new PhutilNumber(count($adjustments))));

      $prompt = pht('Fix these schema issues?');
      if (!phutil_console_confirm($prompt, $default_no = true)) {
        return 1;
      }
    }

    $console->writeOut(
      "%s\n",
      pht('Dropping caches, for faster migrations...'));

    $root = dirname(phutil_get_library_root('phabricator'));
    $bin = $root.'/bin/cache';
    phutil_passthru('%s purge --purge-all', $bin);

    $console->writeOut(
      "%s\n",
      pht('Fixing schema issues...'));

    $conn = $api->getConn(null);

    if ($unsafe) {
      queryfx($conn, 'SET SESSION sql_mode = %s', '');
    } else {
      queryfx($conn, 'SET SESSION sql_mode = %s', 'STRICT_ALL_TABLES');
    }

    $failed = array();

    // We make changes in several phases.
    $phases = array(
      // Drop surplus autoincrements. This allows us to drop primary keys on
      // autoincrement columns.
      'drop_auto',

      // Drop all keys we're going to adjust. This prevents them from
      // interfering with column changes.
      'drop_keys',

      // Apply all database, table, and column changes.
      'main',

      // Restore adjusted keys.
      'add_keys',

      // Add missing autoincrements.
      'add_auto',
    );

    $bar = id(new PhutilConsoleProgressBar())
      ->setTotal(count($adjustments) * count($phases));

    foreach ($phases as $phase) {
      foreach ($adjustments as $adjust) {
        try {
          switch ($adjust['kind']) {
            case 'database':
              if ($phase == 'main') {
                queryfx(
                  $conn,
                  'ALTER DATABASE %T CHARACTER SET = %s COLLATE = %s',
                  $adjust['database'],
                  $adjust['charset'],
                  $adjust['collation']);
              }
              break;
            case 'table':
              if ($phase == 'main') {
                queryfx(
                  $conn,
                  'ALTER TABLE %T.%T COLLATE = %s',
                  $adjust['database'],
                  $adjust['table'],
                  $adjust['collation']);
              }
              break;
            case 'column':
              $apply = false;
              $auto = false;
              $new_auto = idx($adjust, 'auto');
              if ($phase == 'drop_auto') {
                if ($new_auto === false) {
                  $apply = true;
                  $auto = false;
                }
              } else if ($phase == 'main') {
                $apply = true;
                if ($new_auto === false) {
                  $auto = false;
                } else {
                  $auto = $adjust['is_auto'];
                }
              } else if ($phase == 'add_auto') {
                if ($new_auto === true) {
                  $apply = true;
                  $auto = true;
                }
              }

              if ($apply) {
                $parts = array();

                if ($auto) {
                  $parts[] = qsprintf(
                    $conn,
                    'AUTO_INCREMENT');
                }

                if ($adjust['charset']) {
                  $parts[] = qsprintf(
                    $conn,
                    'CHARACTER SET %Q COLLATE %Q',
                    $adjust['charset'],
                    $adjust['collation']);
                }

                queryfx(
                  $conn,
                  'ALTER TABLE %T.%T MODIFY %T %Q %Q %Q',
                  $adjust['database'],
                  $adjust['table'],
                  $adjust['name'],
                  $adjust['type'],
                  implode(' ', $parts),
                  $adjust['nullable'] ? 'NULL' : 'NOT NULL');
              }
              break;
            case 'key':
              if (($phase == 'drop_keys') && $adjust['exists']) {
                if ($adjust['name'] == 'PRIMARY') {
                  $key_name = 'PRIMARY KEY';
                } else {
                  $key_name = qsprintf($conn, 'KEY %T', $adjust['name']);
                }

                queryfx(
                  $conn,
                  'ALTER TABLE %T.%T DROP %Q',
                  $adjust['database'],
                  $adjust['table'],
                  $key_name);
              }

              if (($phase == 'add_keys') && $adjust['keep']) {
                // Different keys need different creation syntax. Notable
                // special cases are primary keys and fulltext keys.
                if ($adjust['name'] == 'PRIMARY') {
                  $key_name = 'PRIMARY KEY';
                } else if ($adjust['indexType'] == 'FULLTEXT') {
                  $key_name = qsprintf($conn, 'FULLTEXT %T', $adjust['name']);
                } else {
                  if ($adjust['unique']) {
                    $key_name = qsprintf(
                      $conn,
                      'UNIQUE KEY %T',
                      $adjust['name']);
                  } else {
                    $key_name = qsprintf(
                      $conn,
                      '/* NONUNIQUE */ KEY %T',
                      $adjust['name']);
                  }
                }

                queryfx(
                  $conn,
                  'ALTER TABLE %T.%T ADD %Q (%Q)',
                  $adjust['database'],
                  $adjust['table'],
                  $key_name,
                  implode(', ', $adjust['columns']));
              }
              break;
            default:
              throw new Exception(
                pht('Unknown schema adjustment kind "%s"!', $adjust['kind']));
          }
        } catch (AphrontQueryException $ex) {
          $failed[] = array($adjust, $ex);
        }
        $bar->update(1);
      }
    }
    $bar->done();

    if (!$failed) {
      $console->writeOut(
        "%s\n",
        pht('Completed fixing all schema issues.'));

      $err = 0;
    } else {
      $table = id(new PhutilConsoleTable())
        ->addColumn('target', array('title' => pht('Target')))
        ->addColumn('error', array('title' => pht('Error')));

      foreach ($failed as $failure) {
        list($adjust, $ex) = $failure;

        $pieces = array_select_keys(
          $adjust,
          array('database', 'table', 'name'));
        $pieces = array_filter($pieces);
        $target = implode('.', $pieces);

        $table->addRow(
          array(
            'target' => $target,
            'error' => $ex->getMessage(),
          ));
      }

      $console->writeOut("\n");
      $table->draw();
      $console->writeOut(
        "\n%s\n",
        pht('Failed to make some schema adjustments, detailed above.'));
      $console->writeOut(
        "%s\n",
        pht(
          'For help troubleshooting adjustments, see "Managing Storage '.
          'Adjustments" in the documentation.'));

      $err = 1;
    }

    return $this->printErrors($errors, $err);
  }

  private function findAdjustments() {
    list($comp, $expect, $actual) = $this->loadSchemata();

    $issue_charset = PhabricatorConfigStorageSchema::ISSUE_CHARSET;
    $issue_collation = PhabricatorConfigStorageSchema::ISSUE_COLLATION;
    $issue_columntype = PhabricatorConfigStorageSchema::ISSUE_COLUMNTYPE;
    $issue_surpluskey = PhabricatorConfigStorageSchema::ISSUE_SURPLUSKEY;
    $issue_missingkey = PhabricatorConfigStorageSchema::ISSUE_MISSINGKEY;
    $issue_columns = PhabricatorConfigStorageSchema::ISSUE_KEYCOLUMNS;
    $issue_unique = PhabricatorConfigStorageSchema::ISSUE_UNIQUE;
    $issue_longkey = PhabricatorConfigStorageSchema::ISSUE_LONGKEY;
    $issue_auto = PhabricatorConfigStorageSchema::ISSUE_AUTOINCREMENT;

    $adjustments = array();
    $errors = array();
    foreach ($comp->getDatabases() as $database_name => $database) {
      foreach ($this->findErrors($database) as $issue) {
        $errors[] = array(
          'database' => $database_name,
          'issue' => $issue,
        );
      }

      $expect_database = $expect->getDatabase($database_name);
      $actual_database = $actual->getDatabase($database_name);

      if (!$expect_database || !$actual_database) {
        // If there's a real issue here, skip this stuff.
        continue;
      }

      $issues = array();
      if ($database->hasIssue($issue_charset)) {
        $issues[] = $issue_charset;
      }
      if ($database->hasIssue($issue_collation)) {
        $issues[] = $issue_collation;
      }

      if ($issues) {
        $adjustments[] = array(
          'kind' => 'database',
          'database' => $database_name,
          'issues' => $issues,
          'charset' => $expect_database->getCharacterSet(),
          'collation' => $expect_database->getCollation(),
        );
      }

      foreach ($database->getTables() as $table_name => $table) {
        foreach ($this->findErrors($table) as $issue) {
          $errors[] = array(
            'database' => $database_name,
            'table' => $table_name,
            'issue' => $issue,
          );
        }

        $expect_table = $expect_database->getTable($table_name);
        $actual_table = $actual_database->getTable($table_name);

        if (!$expect_table || !$actual_table) {
          continue;
        }

        $issues = array();
        if ($table->hasIssue($issue_collation)) {
          $issues[] = $issue_collation;
        }

        if ($issues) {
          $adjustments[] = array(
            'kind' => 'table',
            'database' => $database_name,
            'table' => $table_name,
            'issues' => $issues,
            'collation' => $expect_table->getCollation(),
          );
        }

        foreach ($table->getColumns() as $column_name => $column) {
          foreach ($this->findErrors($column) as $issue) {
            $errors[] = array(
              'database' => $database_name,
              'table' => $table_name,
              'name' => $column_name,
              'issue' => $issue,
            );
          }

          $expect_column = $expect_table->getColumn($column_name);
          $actual_column = $actual_table->getColumn($column_name);

          if (!$expect_column || !$actual_column) {
            continue;
          }

          $issues = array();
          if ($column->hasIssue($issue_collation)) {
            $issues[] = $issue_collation;
          }
          if ($column->hasIssue($issue_charset)) {
            $issues[] = $issue_charset;
          }
          if ($column->hasIssue($issue_columntype)) {
            $issues[] = $issue_columntype;
          }
          if ($column->hasIssue($issue_auto)) {
            $issues[] = $issue_auto;
          }

          if ($issues) {
            if ($expect_column->getCharacterSet() === null) {
              // For non-text columns, we won't be specifying a collation or
              // character set.
              $charset = null;
              $collation = null;
            } else {
              $charset = $expect_column->getCharacterSet();
              $collation = $expect_column->getCollation();
            }

            $adjustment = array(
              'kind' => 'column',
              'database' => $database_name,
              'table' => $table_name,
              'name' => $column_name,
              'issues' => $issues,
              'collation' => $collation,
              'charset' => $charset,
              'type' => $expect_column->getColumnType(),

              // NOTE: We don't adjust column nullability because it is
              // dangerous, so always use the current nullability.
              'nullable' => $actual_column->getNullable(),

              // NOTE: This always stores the current value, because we have
              // to make these updates separately.
              'is_auto' => $actual_column->getAutoIncrement(),
            );

            if ($column->hasIssue($issue_auto)) {
              $adjustment['auto'] = $expect_column->getAutoIncrement();
            }

            $adjustments[] = $adjustment;
          }
        }

        foreach ($table->getKeys() as $key_name => $key) {
          foreach ($this->findErrors($key) as $issue) {
            $errors[] = array(
              'database' => $database_name,
              'table' => $table_name,
              'name' => $key_name,
              'issue' => $issue,
            );
          }

          $expect_key = $expect_table->getKey($key_name);
          $actual_key = $actual_table->getKey($key_name);

          $issues = array();
          $keep_key = true;
          if ($key->hasIssue($issue_surpluskey)) {
            $issues[] = $issue_surpluskey;
            $keep_key = false;
          }

          if ($key->hasIssue($issue_missingkey)) {
            $issues[] = $issue_missingkey;
          }

          if ($key->hasIssue($issue_columns)) {
            $issues[] = $issue_columns;
          }

          if ($key->hasIssue($issue_unique)) {
            $issues[] = $issue_unique;
          }

          // NOTE: We can't really fix this, per se, but we may need to remove
          // the key to change the column type. In the best case, the new
          // column type won't be overlong and recreating the key really will
          // fix the issue. In the worst case, we get the right column type and
          // lose the key, which is still better than retaining the key having
          // the wrong column type.
          if ($key->hasIssue($issue_longkey)) {
            $issues[] = $issue_longkey;
          }

          if ($issues) {
            $adjustment = array(
              'kind' => 'key',
              'database' => $database_name,
              'table' => $table_name,
              'name' => $key_name,
              'issues' => $issues,
              'exists' => (bool)$actual_key,
              'keep' => $keep_key,
            );

            if ($keep_key) {
              $adjustment += array(
                'columns' => $expect_key->getColumnNames(),
                'unique' => $expect_key->getUnique(),
                'indexType' => $expect_key->getIndexType(),
              );
            }

            $adjustments[] = $adjustment;
          }
        }
      }
    }

    return array($adjustments, $errors);
  }

  private function findErrors(PhabricatorConfigStorageSchema $schema) {
    $result = array();
    foreach ($schema->getLocalIssues() as $issue) {
      $status = PhabricatorConfigStorageSchema::getIssueStatus($issue);
      if ($status == PhabricatorConfigStorageSchema::STATUS_FAIL) {
        $result[] = $issue;
      }
    }
    return $result;
  }

  private function printErrors(array $errors, $default_return) {
    if (!$errors) {
      return $default_return;
    }

    $console = PhutilConsole::getConsole();

    $table = id(new PhutilConsoleTable())
      ->addColumn('target', array('title' => pht('Target')))
      ->addColumn('error', array('title' => pht('Error')));

    foreach ($errors as $error) {
      $pieces = array_select_keys(
        $error,
        array('database', 'table', 'name'));
      $pieces = array_filter($pieces);
      $target = implode('.', $pieces);

      $name = PhabricatorConfigStorageSchema::getIssueName($error['issue']);

      $table->addRow(
        array(
          'target' => $target,
          'error' => $name,
        ));
    }

    $console->writeOut("\n");
    $table->draw();
    $console->writeOut("\n");

    $message = pht(
      "The schemata have serious errors (detailed above) which the adjustment ".
      "workflow can not fix.\n\n".
      "If you are not developing Phabricator itself, report this issue to ".
      "the upstream.\n\n".
      "If you are developing Phabricator, these errors usually indicate that ".
      "your schema specifications do not agree with the schemata your code ".
      "actually builds.");

    $console->writeOut(
      "**<bg:red> %s </bg>**\n\n%s\n",
      pht('SCHEMATA ERRORS'),
      phutil_console_wrap($message));

    return 2;
  }

}
