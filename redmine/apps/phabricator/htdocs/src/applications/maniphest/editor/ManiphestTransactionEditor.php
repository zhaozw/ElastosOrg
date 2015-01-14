<?php

final class ManiphestTransactionEditor
  extends PhabricatorApplicationTransactionEditor {

  private $heraldEmailPHIDs = array();

  public function getEditorApplicationClass() {
    return 'PhabricatorManiphestApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Maniphest Tasks');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhabricatorTransactions::TYPE_COMMENT;
    $types[] = PhabricatorTransactions::TYPE_EDGE;
    $types[] = ManiphestTransaction::TYPE_PRIORITY;
    $types[] = ManiphestTransaction::TYPE_STATUS;
    $types[] = ManiphestTransaction::TYPE_TITLE;
    $types[] = ManiphestTransaction::TYPE_DESCRIPTION;
    $types[] = ManiphestTransaction::TYPE_OWNER;
    $types[] = ManiphestTransaction::TYPE_CCS;
    $types[] = ManiphestTransaction::TYPE_SUBPRIORITY;
    $types[] = ManiphestTransaction::TYPE_PROJECT_COLUMN;
    $types[] = ManiphestTransaction::TYPE_MERGED_INTO;
    $types[] = ManiphestTransaction::TYPE_MERGED_FROM;
    $types[] = ManiphestTransaction::TYPE_UNBLOCK;
    $types[] = PhabricatorTransactions::TYPE_VIEW_POLICY;
    $types[] = PhabricatorTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  protected function getCustomTransactionOldValue(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case ManiphestTransaction::TYPE_PRIORITY:
        if ($this->getIsNewObject()) {
          return null;
        }
        return (int)$object->getPriority();
      case ManiphestTransaction::TYPE_STATUS:
        if ($this->getIsNewObject()) {
          return null;
        }
        return $object->getStatus();
      case ManiphestTransaction::TYPE_TITLE:
        if ($this->getIsNewObject()) {
          return null;
        }
        return $object->getTitle();
      case ManiphestTransaction::TYPE_DESCRIPTION:
        if ($this->getIsNewObject()) {
          return null;
        }
        return $object->getDescription();
      case ManiphestTransaction::TYPE_OWNER:
        return nonempty($object->getOwnerPHID(), null);
      case ManiphestTransaction::TYPE_CCS:
        return array_values(array_unique($object->getCCPHIDs()));
      case ManiphestTransaction::TYPE_PROJECT_COLUMN:
        // These are pre-populated.
        return $xaction->getOldValue();
      case ManiphestTransaction::TYPE_SUBPRIORITY:
        return $object->getSubpriority();
      case ManiphestTransaction::TYPE_MERGED_INTO:
      case ManiphestTransaction::TYPE_MERGED_FROM:
        return null;
    }
  }

  protected function getCustomTransactionNewValue(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case ManiphestTransaction::TYPE_PRIORITY:
        return (int)$xaction->getNewValue();
      case ManiphestTransaction::TYPE_CCS:
        return array_values(array_unique($xaction->getNewValue()));
      case ManiphestTransaction::TYPE_OWNER:
        return nonempty($xaction->getNewValue(), null);
      case ManiphestTransaction::TYPE_STATUS:
      case ManiphestTransaction::TYPE_TITLE:
      case ManiphestTransaction::TYPE_DESCRIPTION:
      case ManiphestTransaction::TYPE_SUBPRIORITY:
      case ManiphestTransaction::TYPE_PROJECT_COLUMN:
      case ManiphestTransaction::TYPE_MERGED_INTO:
      case ManiphestTransaction::TYPE_MERGED_FROM:
      case ManiphestTransaction::TYPE_UNBLOCK:
        return $xaction->getNewValue();
    }
  }

  protected function transactionHasEffect(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    $old = $xaction->getOldValue();
    $new = $xaction->getNewValue();

    switch ($xaction->getTransactionType()) {
      case ManiphestTransaction::TYPE_CCS:
        sort($old);
        sort($new);
        return ($old !== $new);
      case ManiphestTransaction::TYPE_PROJECT_COLUMN:
        $new_column_phids = $new['columnPHIDs'];
        $old_column_phids = $old['columnPHIDs'];
        sort($new_column_phids);
        sort($old_column_phids);
        return ($old !== $new);
    }

    return parent::transactionHasEffect($object, $xaction);
  }

  protected function applyCustomInternalTransaction(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case ManiphestTransaction::TYPE_PRIORITY:
        return $object->setPriority($xaction->getNewValue());
      case ManiphestTransaction::TYPE_STATUS:
        return $object->setStatus($xaction->getNewValue());
      case ManiphestTransaction::TYPE_TITLE:
        return $object->setTitle($xaction->getNewValue());
      case ManiphestTransaction::TYPE_DESCRIPTION:
        return $object->setDescription($xaction->getNewValue());
      case ManiphestTransaction::TYPE_OWNER:
        $phid = $xaction->getNewValue();

        // Update the "ownerOrdering" column to contain the full name of the
        // owner, if the task is assigned.

        $handle = null;
        if ($phid) {
          $handle = id(new PhabricatorHandleQuery())
            ->setViewer($this->getActor())
            ->withPHIDs(array($phid))
            ->executeOne();
        }

        if ($handle) {
          $object->setOwnerOrdering($handle->getName());
        } else {
          $object->setOwnerOrdering(null);
        }

        return $object->setOwnerPHID($phid);
      case ManiphestTransaction::TYPE_CCS:
        return $object->setCCPHIDs($xaction->getNewValue());
      case ManiphestTransaction::TYPE_SUBPRIORITY:
        $data = $xaction->getNewValue();
        $new_sub = $this->getNextSubpriority(
          $data['newPriority'],
          $data['newSubpriorityBase'],
          $data['direction']);
        $object->setSubpriority($new_sub);
        return;
      case ManiphestTransaction::TYPE_PROJECT_COLUMN:
        // these do external (edge) updates
        return;
      case ManiphestTransaction::TYPE_MERGED_INTO:
        $object->setStatus(ManiphestTaskStatus::getDuplicateStatus());
        return;
      case ManiphestTransaction::TYPE_MERGED_FROM:
        return;
    }
  }

  protected function expandTransaction(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    $xactions = parent::expandTransaction($object, $xaction);
    switch ($xaction->getTransactionType()) {
      case ManiphestTransaction::TYPE_SUBPRIORITY:
        $data = $xaction->getNewValue();
        $new_pri = $data['newPriority'];
        if ($new_pri != $object->getPriority()) {
          $xactions[] = id(new ManiphestTransaction())
            ->setTransactionType(ManiphestTransaction::TYPE_PRIORITY)
            ->setNewValue($new_pri);
        }
        break;
      default:
        break;
    }

    return $xactions;
  }

  protected function applyCustomExternalTransaction(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case ManiphestTransaction::TYPE_PROJECT_COLUMN:
        $board_phid = idx($xaction->getNewValue(), 'projectPHID');
        if (!$board_phid) {
          throw new Exception(
            pht("Expected 'projectPHID' in column transaction."));
        }

        $old_phids = idx($xaction->getOldValue(), 'columnPHIDs', array());
        $new_phids = idx($xaction->getNewValue(), 'columnPHIDs', array());
        if (count($new_phids) !== 1) {
          throw new Exception(
            pht("Expected exactly one 'columnPHIDs' in column transaction."));
        }

        $columns = id(new PhabricatorProjectColumnQuery())
          ->setViewer($this->requireActor())
          ->withPHIDs($new_phids)
          ->execute();
        $columns = mpull($columns, null, 'getPHID');

        $positions = id(new PhabricatorProjectColumnPositionQuery())
          ->setViewer($this->requireActor())
          ->withObjectPHIDs(array($object->getPHID()))
          ->withBoardPHIDs(array($board_phid))
          ->execute();

        $before_phid = idx($xaction->getNewValue(), 'beforePHID');
        $after_phid = idx($xaction->getNewValue(), 'afterPHID');

        if (!$before_phid && !$after_phid && ($old_phids == $new_phids)) {
          // If we are not moving the object between columns and also not
          // reordering the position, this is a move on some other order
          // (like priority). We can leave the positions untouched and just
          // bail, there's no work to be done.
          return;
        }

        // Otherwise, we're either moving between columns or adjusting the
        // object's position in the "natural" ordering, so we do need to update
        // some rows.

        // Remove all existing column positions on the board.

        foreach ($positions as $position) {
          $position->delete();
        }

        // Add the new column positions.

        foreach ($new_phids as $phid) {
          $column = idx($columns, $phid);
          if (!$column) {
            throw new Exception(
              pht('No such column "%s" exists!', $phid));
          }

          // Load the other object positions in the column. Note that we must
          // skip implicit column creation to avoid generating a new position
          // if the target column is a backlog column.

          $other_positions = id(new PhabricatorProjectColumnPositionQuery())
            ->setViewer($this->requireActor())
            ->withColumns(array($column))
            ->withBoardPHIDs(array($board_phid))
            ->setSkipImplicitCreate(true)
            ->execute();
          $other_positions = msort($other_positions, 'getOrderingKey');

          // Set up the new position object. We're going to figure out the
          // right sequence number and then persist this object with that
          // sequence number.
          $new_position = id(new PhabricatorProjectColumnPosition())
            ->setBoardPHID($board_phid)
            ->setColumnPHID($column->getPHID())
            ->setObjectPHID($object->getPHID());

          $updates = array();
          $sequence = 0;

          // If we're just dropping this into the column without any specific
          // position information, put it at the top.
          if (!$before_phid && !$after_phid) {
            $new_position->setSequence($sequence)->save();
            $sequence++;
          }

          foreach ($other_positions as $position) {
            $object_phid = $position->getObjectPHID();

            // If this is the object we're moving before and we haven't
            // saved yet, insert here.
            if (($before_phid == $object_phid) && !$new_position->getID()) {
              $new_position->setSequence($sequence)->save();
              $sequence++;
            }

            // This object goes here in the sequence; we might need to update
            // the row.
            if ($sequence != $position->getSequence()) {
              $updates[$position->getID()] = $sequence;
            }
            $sequence++;

            // If this is the object we're moving after and we haven't saved
            // yet, insert here.
            if (($after_phid == $object_phid) && !$new_position->getID()) {
              $new_position->setSequence($sequence)->save();
              $sequence++;
            }
          }

          // We should have found a place to put it.
          if (!$new_position->getID()) {
            throw new Exception(
              pht('Unable to find a place to insert object on column!'));
          }

          // If we changed other objects' column positions, bulk reorder them.

          if ($updates) {
            $position = new PhabricatorProjectColumnPosition();
            $conn_w = $position->establishConnection('w');

            $pairs = array();
            foreach ($updates as $id => $sequence) {
              // This is ugly because MySQL gets upset with us if it is
              // configured strictly and we attempt inserts which can't work.
              // We'll never actually do these inserts since they'll always
              // collide (triggering the ON DUPLICATE KEY logic), so we just
              // provide dummy values in order to get there.

              $pairs[] = qsprintf(
                $conn_w,
                '(%d, %d, "", "", "")',
                $id,
                $sequence);
            }

            queryfx(
              $conn_w,
              'INSERT INTO %T (id, sequence, boardPHID, columnPHID, objectPHID)
                VALUES %Q ON DUPLICATE KEY UPDATE sequence = VALUES(sequence)',
              $position->getTableName(),
              implode(', ', $pairs));
          }
        }
        break;
      default:
        break;
    }
  }

  protected function applyFinalEffects(
    PhabricatorLiskDAO $object,
    array $xactions) {

    // When we change the status of a task, update tasks this tasks blocks
    // with a message to the effect of "alincoln resolved blocking task Txxx."
    $unblock_xaction = null;
    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case ManiphestTransaction::TYPE_STATUS:
          $unblock_xaction = $xaction;
          break;
      }
    }

    if ($unblock_xaction !== null) {
      $blocked_phids = PhabricatorEdgeQuery::loadDestinationPHIDs(
        $object->getPHID(),
        PhabricatorEdgeConfig::TYPE_TASK_DEPENDED_ON_BY_TASK);
      if ($blocked_phids) {
        // In theory we could apply these through policies, but that seems a
        // little bit surprising. For now, use the actor's vision.
        $blocked_tasks = id(new ManiphestTaskQuery())
          ->setViewer($this->getActor())
          ->withPHIDs($blocked_phids)
          ->execute();

        $old = $unblock_xaction->getOldValue();
        $new = $unblock_xaction->getNewValue();

        foreach ($blocked_tasks as $blocked_task) {
          $unblock_xactions = array();

          $unblock_xactions[] = id(new ManiphestTransaction())
            ->setTransactionType(ManiphestTransaction::TYPE_UNBLOCK)
            ->setOldValue(array($object->getPHID() => $old))
            ->setNewValue(array($object->getPHID() => $new));

          id(new ManiphestTransactionEditor())
            ->setActor($this->getActor())
            ->setActingAsPHID($this->getActingAsPHID())
            ->setContentSource($this->getContentSource())
            ->setContinueOnNoEffect(true)
            ->setContinueOnMissingFields(true)
            ->applyTransactions($blocked_task, $unblock_xactions);
        }
      }
    }

    return $xactions;
  }

  protected function shouldSendMail(
    PhabricatorLiskDAO $object,
    array $xactions) {

    $xactions = mfilter($xactions, 'shouldHide', true);
    return $xactions;
  }

  protected function getMailSubjectPrefix() {
    return PhabricatorEnv::getEnvConfig('metamta.maniphest.subject-prefix');
  }

  protected function getMailThreadID(PhabricatorLiskDAO $object) {
    return 'maniphest-task-'.$object->getPHID();
  }

  protected function getMailTo(PhabricatorLiskDAO $object) {
    return array(
      $object->getOwnerPHID(),
      $this->getActingAsPHID(),
    );
  }

  protected function getMailCC(PhabricatorLiskDAO $object) {
    $phids = array();

    foreach ($object->getCCPHIDs() as $phid) {
      $phids[] = $phid;
    }

    foreach (parent::getMailCC($object) as $phid) {
      $phids[] = $phid;
    }

    foreach ($this->heraldEmailPHIDs as $phid) {
      $phids[] = $phid;
    }

    return $phids;
  }

  public function getMailTagsMap() {
    return array(
      ManiphestTransaction::MAILTAG_STATUS =>
        pht("A task's status changes."),
      ManiphestTransaction::MAILTAG_OWNER =>
        pht("A task's owner changes."),
      ManiphestTransaction::MAILTAG_PRIORITY =>
        pht("A task's priority changes."),
      ManiphestTransaction::MAILTAG_CC =>
        pht("A task's subscribers change."),
      ManiphestTransaction::MAILTAG_PROJECTS =>
        pht("A task's associated projects change."),
      ManiphestTransaction::MAILTAG_UNBLOCK =>
        pht('One of the tasks a task is blocked by changes status.'),
      ManiphestTransaction::MAILTAG_COLUMN =>
        pht('A task is moved between columns on a workboard.'),
      ManiphestTransaction::MAILTAG_COMMENT =>
        pht('Someone comments on a task.'),
      ManiphestTransaction::MAILTAG_OTHER =>
        pht('Other task activity not listed above occurs.'),
    );
  }

  protected function buildReplyHandler(PhabricatorLiskDAO $object) {
    return id(new ManiphestReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhabricatorLiskDAO $object) {
    $id = $object->getID();
    $title = $object->getTitle();

    return id(new PhabricatorMetaMTAMail())
      ->setSubject("T{$id}: {$title}")
      ->addHeader('Thread-Topic', "T{$id}: ".$object->getOriginalTitle());
  }

  protected function buildMailBody(
    PhabricatorLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    if ($this->getIsNewObject()) {
      $body->addTextSection(
        pht('TASK DESCRIPTION'),
        $object->getDescription());
    }

    $body->addLinkSection(
      pht('TASK DETAIL'),
      PhabricatorEnv::getProductionURI('/T'.$object->getID()));


    $board_phids = array();
    $type_column = ManiphestTransaction::TYPE_PROJECT_COLUMN;
    foreach ($xactions as $xaction) {
      if ($xaction->getTransactionType() == $type_column) {
        $new = $xaction->getNewValue();
        $project_phid = idx($new, 'projectPHID');
        if ($project_phid) {
          $board_phids[] = $project_phid;
        }
      }
    }

    if ($board_phids) {
      $projects = id(new PhabricatorProjectQuery())
        ->setViewer($this->requireActor())
        ->withPHIDs($board_phids)
        ->execute();

      foreach ($projects as $project) {
        $body->addLinkSection(
          pht('WORKBOARD'),
          PhabricatorEnv::getProductionURI(
            '/project/board/'.$project->getID().'/'));
      }
    }


    return $body;
  }

  protected function shouldPublishFeedStory(
    PhabricatorLiskDAO $object,
    array $xactions) {
    return $this->shouldSendMail($object, $xactions);
  }

  protected function supportsSearch() {
    return true;
  }

  protected function shouldApplyHeraldRules(
    PhabricatorLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function buildHeraldAdapter(
    PhabricatorLiskDAO $object,
    array $xactions) {

    return id(new HeraldManiphestTaskAdapter())
      ->setTask($object);
  }

  protected function didApplyHeraldRules(
    PhabricatorLiskDAO $object,
    HeraldAdapter $adapter,
    HeraldTranscript $transcript) {

    // TODO: Convert these to transactions. The way Maniphest deals with these
    // transactions is currently unconventional and messy.

    $save_again = false;
    $cc_phids = $adapter->getCcPHIDs();
    if ($cc_phids) {
      $existing_cc = $object->getCCPHIDs();
      $new_cc = array_unique(array_merge($cc_phids, $existing_cc));
      $object->setCCPHIDs($new_cc);
      $object->save();
    }

    $this->heraldEmailPHIDs = $adapter->getEmailPHIDs();

    $xactions = array();

    $assign_phid = $adapter->getAssignPHID();
    if ($assign_phid) {
      $xactions[] = id(new ManiphestTransaction())
        ->setTransactionType(ManiphestTransaction::TYPE_OWNER)
        ->setNewValue($assign_phid);
    }

    $project_phids = $adapter->getProjectPHIDs();
    if ($project_phids) {
      $project_type = PhabricatorProjectObjectHasProjectEdgeType::EDGECONST;
      $xactions[] = id(new ManiphestTransaction())
        ->setTransactionType(PhabricatorTransactions::TYPE_EDGE)
        ->setMetadataValue('edge:type', $project_type)
        ->setNewValue(
          array(
            '+' => array_fuse($project_phids),
          ));
    }

    return $xactions;
  }

  protected function requireCapabilities(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    parent::requireCapabilities($object, $xaction);

    $app_capability_map = array(
      ManiphestTransaction::TYPE_PRIORITY =>
        ManiphestEditPriorityCapability::CAPABILITY,
      ManiphestTransaction::TYPE_STATUS =>
        ManiphestEditStatusCapability::CAPABILITY,
      ManiphestTransaction::TYPE_OWNER =>
        ManiphestEditAssignCapability::CAPABILITY,
      PhabricatorTransactions::TYPE_EDIT_POLICY =>
        ManiphestEditPoliciesCapability::CAPABILITY,
      PhabricatorTransactions::TYPE_VIEW_POLICY =>
        ManiphestEditPoliciesCapability::CAPABILITY,
    );


    $transaction_type = $xaction->getTransactionType();

    $app_capability = null;
    if ($transaction_type == PhabricatorTransactions::TYPE_EDGE) {
      switch ($xaction->getMetadataValue('edge:type')) {
        case PhabricatorProjectObjectHasProjectEdgeType::EDGECONST:
          $app_capability = ManiphestEditProjectsCapability::CAPABILITY;
          break;
      }
    } else {
      $app_capability = idx($app_capability_map, $transaction_type);
    }

    if ($app_capability) {
      $app = id(new PhabricatorApplicationQuery())
        ->setViewer($this->getActor())
        ->withClasses(array('PhabricatorManiphestApplication'))
        ->executeOne();
      PhabricatorPolicyFilter::requireCapability(
        $this->getActor(),
        $app,
        $app_capability);
    }
  }

  protected function adjustObjectForPolicyChecks(
    PhabricatorLiskDAO $object,
    array $xactions) {

    $copy = parent::adjustObjectForPolicyChecks($object, $xactions);
    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case ManiphestTransaction::TYPE_OWNER:
          $copy->setOwnerPHID($xaction->getNewValue());
          break;
        default:
          continue;
      }
    }

    return $copy;
  }

  private function getNextSubpriority($pri, $sub, $dir = '>') {
    switch ($dir) {
      case '>':
        $order = 'ASC';
        break;
      case '<':
        $order = 'DESC';
        break;
      default:
        throw new Exception('$dir must be ">" or "<".');
        break;
    }

    if ($sub === null) {
      $base = 0;
    } else {
      $base = $sub;
    }

    if ($sub === null) {
      $next = id(new ManiphestTask())->loadOneWhere(
        'priority = %d ORDER BY subpriority %Q LIMIT 1',
        $pri,
        $order);
      if ($next) {
        if ($dir == '>') {
          return $next->getSubpriority() - ((double)(2 << 16));
        } else {
          return $next->getSubpriority() + ((double)(2 << 16));
        }
      }
    } else {
      $next = id(new ManiphestTask())->loadOneWhere(
        'priority = %d AND subpriority %Q %f ORDER BY subpriority %Q LIMIT 1',
        $pri,
        $dir,
        $sub,
        $order);
      if ($next) {
        return ($sub + $next->getSubpriority()) / 2;
      }
    }

    if ($dir == '>') {
      return $base + (double)(2 << 32);
    } else {
      return $base - (double)(2 << 32);
    }
  }

}
