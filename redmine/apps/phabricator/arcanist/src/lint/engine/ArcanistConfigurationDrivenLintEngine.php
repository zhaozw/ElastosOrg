<?php

final class ArcanistConfigurationDrivenLintEngine extends ArcanistLintEngine {

  public function buildLinters() {
    $working_copy = $this->getWorkingCopy();
    $config_path = $working_copy->getProjectPath('.arclint');

    if (!Filesystem::pathExists($config_path)) {
      throw new Exception(
        "Unable to find '.arclint' file to configure linters. Create a ".
        "'.arclint' file in the root directory of the working copy.");
    }

    $data = Filesystem::readFile($config_path);
    $config = null;
    try {
      $config = phutil_json_decode($data);
    } catch (PhutilJSONParserException $ex) {
      throw new PhutilProxyException(
        pht(
          "Expected '.arclint' file to be a valid JSON file, but failed to ".
          "decode %s",
          $config_path),
        $ex);
    }

    $linters = $this->loadAvailableLinters();

    try {
      PhutilTypeSpec::checkMap(
        $config,
        array(
          'exclude' => 'optional regex | list<regex>',
          'linters' => 'map<string, map<string, wild>>',
        ));
    } catch (PhutilTypeCheckException $ex) {
      $message = pht(
        'Error in parsing ".arclint" file: %s',
        $ex->getMessage());
      throw new PhutilProxyException($message, $ex);
    }

    $global_exclude = (array)idx($config, 'exclude', array());

    $built_linters = array();
    $all_paths = $this->getPaths();
    foreach ($config['linters'] as $name => $spec) {
      $type = idx($spec, 'type');
      if ($type !== null) {
        if (empty($linters[$type])) {
          $list = implode(', ', array_keys($linters));
          throw new Exception(
            "Linter '{$name}' specifies invalid type '{$type}'. Available ".
            "linters are: {$list}.");
        }

        $linter = clone $linters[$type];
        $linter->setEngine($this);
        $more = $linter->getLinterConfigurationOptions();

        foreach ($more as $key => $option_spec) {
          PhutilTypeSpec::checkMap(
            $option_spec,
            array(
              'type' => 'string',
              'help' => 'string',
            ));
          $more[$key] = $option_spec['type'];
        }
      } else {
        // We'll raise an error below about the invalid "type" key.
        $linter = null;
        $more = array();
      }

      try {
        PhutilTypeSpec::checkMap(
          $spec,
          array(
            'type' => 'string',
            'include' => 'optional regex | list<regex>',
            'exclude' => 'optional regex | list<regex>',
          ) + $more);
      } catch (PhutilTypeCheckException $ex) {
        $message = pht(
          'Error in parsing ".arclint" file, for linter "%s": %s',
          $name,
          $ex->getMessage());
        throw new PhutilProxyException($message, $ex);
      }

      foreach ($more as $key => $value) {
        if (array_key_exists($key, $spec)) {
          try {
            $linter->setLinterConfigurationValue($key, $spec[$key]);
          } catch (Exception $ex) {
            $message = pht(
              'Error in parsing ".arclint" file, in key "%s" for '.
              'linter "%s": %s',
              $key,
              $name,
              $ex->getMessage());
            throw new PhutilProxyException($message, $ex);
          }
        }
      }

      $include = (array)idx($spec, 'include', array());
      $exclude = (array)idx($spec, 'exclude', array());

      $console = PhutilConsole::getConsole();
      $console->writeLog("Examining paths for linter \"%s\".\n", $name);
      $paths = $this->matchPaths(
        $all_paths,
        $include,
        $exclude,
        $global_exclude);
      $console->writeLog(
        "Found %d matching paths for linter \"%s\".\n",
        count($paths),
        $name);

      if ($paths) {
        $linter->setPaths($paths);
        $built_linters[] = $linter;
      }
    }

    return $built_linters;
  }

  private function loadAvailableLinters() {
    $linters = id(new PhutilSymbolLoader())
      ->setAncestorClass('ArcanistLinter')
      ->loadObjects();

    $map = array();
    foreach ($linters as $linter) {
      $name = $linter->getLinterConfigurationName();

      // This linter isn't selectable through configuration.
      if ($name === null) {
        continue;
      }

      if (empty($map[$name])) {
        $map[$name] = $linter;
        continue;
      }

      $orig_class = get_class($map[$name]);
      $this_class = get_class($linter);
      throw new Exception(
        "Two linters ({$orig_class}, {$this_class}) both have the same ".
        "configuration name ({$name}). Linters must have unique configuration ".
        "names.");
    }

    return $map;
  }

  private function matchPaths(
    array $paths,
    array $include,
    array $exclude,
    array $global_exclude) {

    $console = PhutilConsole::getConsole();

    $match = array();
    foreach ($paths as $path) {
      $console->writeLog("Examining path '%s'...\n", $path);

      $keep = false;
      if (!$include) {
        $keep = true;
        $console->writeLog(
          "  Including path by default because there is no 'include' rule.\n");
      } else {
        $console->writeLog("  Testing \"include\" rules.\n");
        foreach ($include as $rule) {
          if (preg_match($rule, $path)) {
            $keep = true;
            $console->writeLog("  Path matches include rule: %s\n", $rule);
            break;
          } else {
            $console->writeLog(
              "  Path does not match include rule: %s\n",
              $rule);
          }
        }
      }

      if (!$keep) {
        $console->writeLog(
          "  Path does not match any include rules, discarding.\n");
        continue;
      }

      if ($exclude) {
        $console->writeLog("  Testing \"exclude\" rules.\n");
        foreach ($exclude as $rule) {
          if (preg_match($rule, $path)) {
            $console->writeLog("  Path matches \"exclude\" rule: %s\n", $rule);
            continue 2;
          } else {
            $console->writeLog(
              "  Path does not match \"exclude\" rule: %s\n",
              $rule);
          }
        }
      }

      if ($global_exclude) {
        $console->writeLog("  Testing global \"exclude\" rules.\n");
        foreach ($global_exclude as $rule) {
          if (preg_match($rule, $path)) {
            $console->writeLog(
              "  Path matches global \"exclude\" rule: %s\n",
              $rule);
            continue 2;
          } else {
            $console->writeLog(
              "  Path does not match global \"exclude\" rule: %s\n",
              $rule);
          }
        }
      }

      $console->writeLog("  Path matches.\n");
      $match[] = $path;
    }

    return $match;
  }

}
