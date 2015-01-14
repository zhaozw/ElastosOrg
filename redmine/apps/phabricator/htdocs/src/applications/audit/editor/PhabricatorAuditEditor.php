<?php

final class PhabricatorAuditEditor
  extends PhabricatorApplicationTransactionEditor {

  const MAX_FILES_SHOWN_IN_EMAIL = 1000;

  private $auditReasonMap = array();
  private $heraldEmailPHIDs = array();
  private $affectedFiles;
  private $rawPatch;

  public function addAuditReason($phid, $reason) {
    if (!isset($this->auditReasonMap[$phid])) {
      $this->auditReasonMap[$phid] = array();
    }
    $this->auditReasonMap[$phid][] = $reason;
    return $this;
  }

  private function getAuditReasons($phid) {
    if (isset($this->auditReasonMap[$phid])) {
      return $this->auditReasonMap[$phid];
    }
    if ($this->getIsHeraldEditor()) {
      $name = 'herald';
    } else {
      $name = $this->getActor()->getUsername();
    }
    return array('Added by '.$name.'.');
  }

  public function setRawPatch($patch) {
    $this->rawPatch = $patch;
    return $this;
  }

  public function getRawPatch() {
    return $this->rawPatch;
  }

  public function getEditorApplicationClass() {
    return 'PhabricatorAuditApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Audits');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhabricatorTransactions::TYPE_COMMENT;
    $types[] = PhabricatorTransactions::TYPE_EDGE;

    $types[] = PhabricatorAuditTransaction::TYPE_COMMIT;

    // TODO: These will get modernized eventually, but that can happen one
    // at a time later on.
    $types[] = PhabricatorAuditActionConstants::ACTION;
    $types[] = PhabricatorAuditActionConstants::INLINE;
    $types[] = PhabricatorAuditActionConstants::ADD_AUDITORS;

    return $types;
  }

  protected function transactionHasEffect(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhabricatorAuditActionConstants::INLINE:
        return $xaction->hasComment();
    }

    return parent::transactionHasEffect($object, $xaction);
  }

  protected function getCustomTransactionOldValue(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {
    switch ($xaction->getTransactionType()) {
      case PhabricatorAuditActionConstants::ACTION:
      case PhabricatorAuditActionConstants::INLINE:
      case PhabricatorAuditTransaction::TYPE_COMMIT:
        return null;
      case PhabricatorAuditActionConstants::ADD_AUDITORS:
        // TODO: For now, just record the added PHIDs. Eventually, turn these
        // into real edge transactions, probably?
        return array();
    }

    return parent::getCustomTransactionOldValue($object, $xaction);
  }

  protected function getCustomTransactionNewValue(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhabricatorAuditActionConstants::ACTION:
      case PhabricatorAuditActionConstants::INLINE:
      case PhabricatorAuditActionConstants::ADD_AUDITORS:
      case PhabricatorAuditTransaction::TYPE_COMMIT:
        return $xaction->getNewValue();
    }

    return parent::getCustomTransactionNewValue($object, $xaction);
  }

  protected function applyCustomInternalTransaction(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhabricatorTransactions::TYPE_COMMENT:
      case PhabricatorTransactions::TYPE_SUBSCRIBERS:
      case PhabricatorTransactions::TYPE_EDGE:
      case PhabricatorAuditActionConstants::ACTION:
      case PhabricatorAuditActionConstants::INLINE:
      case PhabricatorAuditActionConstants::ADD_AUDITORS:
      case PhabricatorAuditTransaction::TYPE_COMMIT:
        return;
    }

    return parent::applyCustomInternalTransaction($object, $xaction);
  }

  protected function applyCustomExternalTransaction(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhabricatorTransactions::TYPE_COMMENT:
      case PhabricatorTransactions::TYPE_SUBSCRIBERS:
      case PhabricatorTransactions::TYPE_EDGE:
      case PhabricatorAuditActionConstants::ACTION:
      case PhabricatorAuditActionConstants::INLINE:
      case PhabricatorAuditTransaction::TYPE_COMMIT:
        return;
      case PhabricatorAuditActionConstants::ADD_AUDITORS:
        $new = $xaction->getNewValue();
        if (!is_array($new)) {
          $new = array();
        }

        $old = $xaction->getOldValue();
        if (!is_array($old)) {
          $old = array();
        }

        $add = array_diff_key($new, $old);

        $actor = $this->requireActor();

        $requests = $object->getAudits();
        $requests = mpull($requests, null, 'getAuditorPHID');
        foreach ($add as $phid) {
          if (isset($requests[$phid])) {
            continue;
          }

          if ($this->getIsHeraldEditor()) {
            $audit_requested = $xaction->getMetadataValue('auditStatus');
            $audit_reason_map = $xaction->getMetadataValue('auditReasonMap');
            $audit_reason = $audit_reason_map[$phid];
          } else {
            $audit_requested = PhabricatorAuditStatusConstants::AUDIT_REQUESTED;
            $audit_reason = $this->getAuditReasons($phid);
          }
          $requests[] = id (new PhabricatorRepositoryAuditRequest())
            ->setCommitPHID($object->getPHID())
            ->setAuditorPHID($phid)
            ->setAuditStatus($audit_requested)
            ->setAuditReasons($audit_reason)
            ->save();
        }

        $object->attachAudits($requests);
        return;
    }

    return parent::applyCustomExternalTransaction($object, $xaction);
  }

  protected function applyFinalEffects(
    PhabricatorLiskDAO $object,
    array $xactions) {

    // Load auditors explicitly; we may not have them if the caller was a
    // generic piece of infrastructure.

    $commit = id(new DiffusionCommitQuery())
      ->setViewer($this->requireActor())
      ->withIDs(array($object->getID()))
      ->needAuditRequests(true)
      ->executeOne();
    if (!$commit) {
      throw new Exception(
        pht('Failed to load commit during transaction finalization!'));
    }
    $object->attachAudits($commit->getAudits());

    $status_concerned = PhabricatorAuditStatusConstants::CONCERNED;
    $status_closed = PhabricatorAuditStatusConstants::CLOSED;
    $status_resigned = PhabricatorAuditStatusConstants::RESIGNED;
    $status_accepted = PhabricatorAuditStatusConstants::ACCEPTED;
    $status_concerned = PhabricatorAuditStatusConstants::CONCERNED;

    $actor_phid = $this->getActingAsPHID();
    $actor_is_author = ($object->getAuthorPHID()) &&
      ($actor_phid == $object->getAuthorPHID());

    $import_status_flag = null;
    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case PhabricatorAuditTransaction::TYPE_COMMIT:
          $import_status_flag = PhabricatorRepositoryCommit::IMPORTED_HERALD;
          break;
        case PhabricatorAuditActionConstants::ACTION:
          $new = $xaction->getNewValue();
          switch ($new) {
            case PhabricatorAuditActionConstants::CLOSE:
              // "Close" means wipe out all the concerns.
              $requests = $object->getAudits();
              foreach ($requests as $request) {
                if ($request->getAuditStatus() == $status_concerned) {
                  $request
                    ->setAuditStatus($status_closed)
                    ->save();
                }
              }
              break;
            case PhabricatorAuditActionConstants::RESIGN:
              $requests = $object->getAudits();
              $requests = mpull($requests, null, 'getAuditorPHID');
              $actor_request = idx($requests, $actor_phid);

              // If the actor doesn't currently have a relationship to the
              // commit, add one explicitly. For example, this allows members
              // of a project to resign from a commit and have it drop out of
              // their queue.

              if (!$actor_request) {
                $actor_request = id(new PhabricatorRepositoryAuditRequest())
                  ->setCommitPHID($object->getPHID())
                  ->setAuditorPHID($actor_phid);

                $requests[] = $actor_request;
                $object->attachAudits($requests);
              }

              $actor_request
                ->setAuditStatus($status_resigned)
                ->save();
              break;
            case PhabricatorAuditActionConstants::ACCEPT:
            case PhabricatorAuditActionConstants::CONCERN:
              if ($new == PhabricatorAuditActionConstants::ACCEPT) {
                $new_status = $status_accepted;
              } else {
                $new_status = $status_concerned;
              }

              $requests = $object->getAudits();
              $requests = mpull($requests, null, 'getAuditorPHID');

              // Figure out which requests the actor has authority over: these
              // are user requests where they are the auditor, and packages
              // and projects they are a member of.

              if ($actor_is_author) {
                // When modifying your own commits, you act only on behalf of
                // yourself, not your packages/projects -- the idea being that
                // you can't accept your own commits.
                $authority_phids = array($actor_phid);
              } else {
                $authority_phids =
                  PhabricatorAuditCommentEditor::loadAuditPHIDsForUser(
                    $this->requireActor());
              }

              $authority = array_select_keys(
                $requests,
                $authority_phids);

              if (!$authority) {
                // If the actor has no authority over any existing requests,
                // create a new request for them.

                $actor_request = id(new PhabricatorRepositoryAuditRequest())
                  ->setCommitPHID($object->getPHID())
                  ->setAuditorPHID($actor_phid)
                  ->setAuditStatus($new_status)
                  ->save();

                $requests[$actor_phid] = $actor_request;
                $object->attachAudits($requests);
              } else {
                // Otherwise, update the audit status of the existing requests.
                foreach ($authority as $request) {
                  $request
                    ->setAuditStatus($new_status)
                    ->save();
                }
              }
              break;

          }
          break;
      }
    }

    $requests = $object->getAudits();
    $object->updateAuditStatus($requests);
    $object->save();

    if ($import_status_flag) {
      $object->writeImportStatusFlag($import_status_flag);
    }

    return $xactions;
  }

  protected function expandTransaction(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    $xactions = parent::expandTransaction($object, $xaction);
    switch ($xaction->getTransactionType()) {
      case PhabricatorAuditTransaction::TYPE_COMMIT:
        $request = $this->createAuditRequestTransactionFromCommitMessage(
          $object);
        if ($request) {
          $xactions[] = $request;
          $this->setUnmentionablePHIDMap($request->getNewValue());
        }
        break;
      default:
        break;
    }
    return $xactions;
  }

  private function createAuditRequestTransactionFromCommitMessage(
    PhabricatorRepositoryCommit $commit) {

    $data = $commit->getCommitData();
    $message = $data->getCommitMessage();

    $matches = null;
    if (!preg_match('/^Auditors:\s*(.*)$/im', $message, $matches)) {
      return array();
    }

    $phids = id(new PhabricatorObjectListQuery())
      ->setViewer($this->getActor())
      ->setAllowPartialResults(true)
      ->setAllowedTypes(
        array(
          PhabricatorPeopleUserPHIDType::TYPECONST,
          PhabricatorProjectProjectPHIDType::TYPECONST,
        ))
      ->setObjectList($matches[1])
      ->execute();

    if (!$phids) {
      return array();
    }

    foreach ($phids as $phid) {
      $this->addAuditReason($phid, 'Requested by Author');
    }
    return id(new PhabricatorAuditTransaction())
      ->setTransactionType(PhabricatorAuditActionConstants::ADD_AUDITORS)
      ->setNewValue(array_fuse($phids));
  }

  protected function sortTransactions(array $xactions) {
    $xactions = parent::sortTransactions($xactions);

    $head = array();
    $tail = array();

    foreach ($xactions as $xaction) {
      $type = $xaction->getTransactionType();
      if ($type == PhabricatorAuditActionConstants::INLINE) {
        $tail[] = $xaction;
      } else {
        $head[] = $xaction;
      }
    }

    return array_values(array_merge($head, $tail));
  }

  protected function validateTransaction(
    PhabricatorLiskDAO $object,
    $type,
    array $xactions) {

    $errors = parent::validateTransaction($object, $type, $xactions);

    foreach ($xactions as $xaction) {
      switch ($type) {
        case PhabricatorAuditActionConstants::ACTION:
          $error = $this->validateAuditAction(
            $object,
            $type,
            $xaction,
            $xaction->getNewValue());
          if ($error) {
            $errors[] = new PhabricatorApplicationTransactionValidationError(
              $type,
              pht('Invalid'),
              $error,
              $xaction);
          }
          break;
      }
    }

    return $errors;
  }

  private function validateAuditAction(
    PhabricatorLiskDAO $object,
    $type,
    PhabricatorAuditTransaction $xaction,
    $action) {

    $can_author_close_key = 'audit.can-author-close-audit';
    $can_author_close = PhabricatorEnv::getEnvConfig($can_author_close_key);

    $actor_is_author = ($object->getAuthorPHID()) &&
      ($object->getAuthorPHID() == $this->getActingAsPHID());

    switch ($action) {
      case PhabricatorAuditActionConstants::CLOSE:
        if (!$actor_is_author) {
          return pht(
            'You can not close this audit because you are not the author '.
            'of the commit.');
        }

        if (!$can_author_close) {
          return pht(
            'You can not close this audit because "%s" is disabled in '.
            'the Phabricator configuration.',
            $can_author_close_key);
        }

        break;
    }

    return null;
  }


  protected function supportsSearch() {
    return true;
  }

  protected function expandCustomRemarkupBlockTransactions(
    PhabricatorLiskDAO $object,
    array $xactions,
    $blocks,
    PhutilMarkupEngine $engine) {

    // we are only really trying to find unmentionable phids here...
    // don't bother with this outside initial commit (i.e. create)
    // transaction
    $is_commit = false;
    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case PhabricatorAuditTransaction::TYPE_COMMIT:
          $is_commit = true;
          break;
      }
    }

    // "result" is always an array....
    $result = array();
    if (!$is_commit) {
      return $result;
    }

    $flat_blocks = array_mergev($blocks);
    $huge_block = implode("\n\n", $flat_blocks);
    $phid_map = array();
    $phid_map[] = $this->getUnmentionablePHIDMap();
    $monograms = array();

    $task_refs = id(new ManiphestCustomFieldStatusParser())
      ->parseCorpus($huge_block);
    foreach ($task_refs as $match) {
      foreach ($match['monograms'] as $monogram) {
        $monograms[] = $monogram;
      }
    }

    $rev_refs = id(new DifferentialCustomFieldDependsOnParser())
      ->parseCorpus($huge_block);
    foreach ($rev_refs as $match) {
      foreach ($match['monograms'] as $monogram) {
        $monograms[] = $monogram;
      }
    }

    $objects = id(new PhabricatorObjectQuery())
      ->setViewer($this->getActor())
      ->withNames($monograms)
      ->execute();
    $phid_map[] = mpull($objects, 'getPHID', 'getPHID');
    $phid_map = array_mergev($phid_map);
    $this->setUnmentionablePHIDMap($phid_map);

    return $result;
  }


  protected function shouldSendMail(
    PhabricatorLiskDAO $object,
    array $xactions) {

    // not every code path loads the repository so tread carefully
    if ($object->getRepository($assert_attached = false)) {
      $repository = $object->getRepository();
      if ($repository->isImporting()) {
        return false;
      }
    }
    return $this->isCommitMostlyImported($object);
  }

  protected function buildReplyHandler(PhabricatorLiskDAO $object) {
    $reply_handler = PhabricatorEnv::newObjectFromConfig(
      'metamta.diffusion.reply-handler');
    $reply_handler->setMailReceiver($object);
    return $reply_handler;
  }

  protected function getMailSubjectPrefix() {
    return PhabricatorEnv::getEnvConfig('metamta.diffusion.subject-prefix');
  }

  protected function getMailThreadID(PhabricatorLiskDAO $object) {
    // For backward compatibility, use this legacy thread ID.
    return 'diffusion-audit-'.$object->getPHID();
  }

  protected function buildMailTemplate(PhabricatorLiskDAO $object) {
    $identifier = $object->getCommitIdentifier();
    $repository = $object->getRepository();
    $monogram = $repository->getMonogram();

    $summary = $object->getSummary();
    $name = $repository->formatCommitName($identifier);

    $subject = "{$name}: {$summary}";
    $thread_topic = "Commit {$monogram}{$identifier}";

    $template = id(new PhabricatorMetaMTAMail())
      ->setSubject($subject)
      ->addHeader('Thread-Topic', $thread_topic);

    $this->attachPatch(
      $template,
      $object);

    return $template;
  }

  protected function getMailTo(PhabricatorLiskDAO $object) {
    $phids = array();
    if ($this->heraldEmailPHIDs) {
      $phids = $this->heraldEmailPHIDs;
    }

    if ($object->getAuthorPHID()) {
      $phids[] = $object->getAuthorPHID();
    }

    $status_resigned = PhabricatorAuditStatusConstants::RESIGNED;
    foreach ($object->getAudits() as $audit) {
      if ($audit->getAuditStatus() != $status_resigned) {
        $phids[] = $audit->getAuditorPHID();
      }
    }

    return $phids;
  }

  protected function buildMailBody(
    PhabricatorLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    $type_inline = PhabricatorAuditActionConstants::INLINE;
    $type_push = PhabricatorAuditTransaction::TYPE_COMMIT;

    $is_commit = false;
    $inlines = array();
    foreach ($xactions as $xaction) {
      if ($xaction->getTransactionType() == $type_inline) {
        $inlines[] = $xaction;
      }
      if ($xaction->getTransactionType() == $type_push) {
        $is_commit = true;
      }
    }

    if ($inlines) {
      $body->addTextSection(
        pht('INLINE COMMENTS'),
        $this->renderInlineCommentsForMail($object, $inlines));
    }

    if ($is_commit) {
      $data = $object->getCommitData();
      $body->addTextSection(pht('AFFECTED FILES'), $this->affectedFiles);
      $this->inlinePatch(
        $body,
        $object);
    }

    // Reload the commit to pull commit data.
    $commit = id(new DiffusionCommitQuery())
      ->setViewer($this->requireActor())
      ->withIDs(array($object->getID()))
      ->needCommitData(true)
      ->executeOne();
    $data = $commit->getCommitData();

    $user_phids = array();

    $author_phid = $commit->getAuthorPHID();
    if ($author_phid) {
      $user_phids[$author_phid][] = pht('Author');
    }

    $committer_phid = $data->getCommitDetail('committerPHID');
    if ($committer_phid && ($committer_phid != $author_phid)) {
      $user_phids[$committer_phid][] = pht('Committer');
    }

    // we loaded this in applyFinalEffects
    $audit_requests = $object->getAudits();
    $auditor_phids = mpull($audit_requests, 'getAuditorPHID');
    foreach ($auditor_phids as $auditor_phid) {
      $user_phids[$auditor_phid][] = pht('Auditor');
    }

    // TODO: It would be nice to show pusher here too, but that information
    // is a little tricky to get at right now.

    if ($user_phids) {
      $handle_phids = array_keys($user_phids);
      $handles = id(new PhabricatorHandleQuery())
        ->setViewer($this->requireActor())
        ->withPHIDs($handle_phids)
        ->execute();

      $user_info = array();
      foreach ($user_phids as $phid => $roles) {
        $user_info[] = pht(
          '%s (%s)',
          $handles[$phid]->getName(),
          implode(', ', $roles));
      }

      $body->addTextSection(
        pht('USERS'),
        implode("\n", $user_info));
    }

    $monogram = $object->getRepository()->formatCommitName(
      $object->getCommitIdentifier());

    $body->addLinkSection(
      pht('COMMIT'),
      PhabricatorEnv::getProductionURI('/'.$monogram));

    return $body;
  }

  private function attachPatch(
    PhabricatorMetaMTAMail $template,
    PhabricatorRepositoryCommit $commit) {

    if (!$this->getRawPatch()) {
      return;
    }

    $attach_key = 'metamta.diffusion.attach-patches';
    $attach_patches = PhabricatorEnv::getEnvConfig($attach_key);
    if (!$attach_patches) {
      return;
    }

    $repository = $commit->getRepository();
    $encoding = $repository->getDetail('encoding', 'UTF-8');

    $raw_patch = $this->getRawPatch();
    $commit_name = $repository->formatCommitName(
      $commit->getCommitIdentifier());

    $template->addAttachment(
      new PhabricatorMetaMTAAttachment(
        $raw_patch,
        $commit_name.'.patch',
        'text/x-patch; charset='.$encoding));
  }

  private function inlinePatch(
    PhabricatorMetaMTAMailBody $body,
    PhabricatorRepositoryCommit $commit) {

    if (!$this->getRawPatch()) {
        return;
    }

    $inline_key = 'metamta.diffusion.inline-patches';
    $inline_patches = PhabricatorEnv::getEnvConfig($inline_key);
    if (!$inline_patches) {
      return;
    }

    $repository = $commit->getRepository();
    $raw_patch = $this->getRawPatch();
    $result = null;
    $len = substr_count($raw_patch, "\n");
    if ($len <= $inline_patches) {
      // We send email as utf8, so we need to convert the text to utf8 if
      // we can.
      $encoding = $repository->getDetail('encoding', 'UTF-8');
      if ($encoding) {
        $raw_patch = phutil_utf8_convert($raw_patch, 'UTF-8', $encoding);
      }
      $result = phutil_utf8ize($raw_patch);
    }

    if ($result) {
      $result = "PATCH\n\n{$result}\n";
    }
    $body->addRawSection($result);
  }

  private function renderInlineCommentsForMail(
    PhabricatorLiskDAO $object,
    array $inline_xactions) {

    $inlines = mpull($inline_xactions, 'getComment');

    $block = array();

    $path_map = id(new DiffusionPathQuery())
      ->withPathIDs(mpull($inlines, 'getPathID'))
      ->execute();
    $path_map = ipull($path_map, 'path', 'id');

    foreach ($inlines as $inline) {
      $path = idx($path_map, $inline->getPathID());
      if ($path === null) {
        continue;
      }

      $start = $inline->getLineNumber();
      $len   = $inline->getLineLength();
      if ($len) {
        $range = $start.'-'.($start + $len);
      } else {
        $range = $start;
      }

      $content = $inline->getContent();
      $block[] = "{$path}:{$range} {$content}";
    }

    return implode("\n", $block);
  }

  public function getMailTagsMap() {
    return array(
      PhabricatorAuditTransaction::MAILTAG_COMMIT =>
        pht('A commit is created.'),
      PhabricatorAuditTransaction::MAILTAG_ACTION_CONCERN =>
        pht('A commit has a concerned raised against it.'),
      PhabricatorAuditTransaction::MAILTAG_ACTION_ACCEPT =>
        pht('A commit is accepted.'),
      PhabricatorAuditTransaction::MAILTAG_ACTION_RESIGN =>
        pht('A commit has an auditor resign.'),
      PhabricatorAuditTransaction::MAILTAG_ACTION_CLOSE =>
        pht('A commit is closed.'),
      PhabricatorAuditTransaction::MAILTAG_ADD_AUDITORS =>
        pht('A commit has auditors added.'),
      PhabricatorAuditTransaction::MAILTAG_ADD_CCS =>
        pht("A commit's subscribers change."),
      PhabricatorAuditTransaction::MAILTAG_PROJECTS =>
        pht("A commit's projects change."),
      PhabricatorAuditTransaction::MAILTAG_COMMENT =>
        pht('Someone comments on a commit.'),
      PhabricatorAuditTransaction::MAILTAG_OTHER =>
        pht('Other commit activity not listed above occurs.'),
    );
  }



  protected function shouldPublishFeedStory(
    PhabricatorLiskDAO $object,
    array $xactions) {
    return $this->shouldSendMail($object, $xactions);
  }

  protected function shouldApplyHeraldRules(
    PhabricatorLiskDAO $object,
    array $xactions) {

    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case PhabricatorAuditTransaction::TYPE_COMMIT:
          $repository = $object->getRepository();
          if ($repository->isImporting()) {
            return false;
          }
          if ($repository->getDetail('herald-disabled')) {
            return false;
          }
          return true;
        default:
          break;
      }
    }
    return parent::shouldApplyHeraldRules($object, $xactions);
  }

  protected function buildHeraldAdapter(
    PhabricatorLiskDAO $object,
    array $xactions) {

    return id(new HeraldCommitAdapter())
      ->setCommit($object);
  }

  protected function didApplyHeraldRules(
    PhabricatorLiskDAO $object,
    HeraldAdapter $adapter,
    HeraldTranscript $transcript) {

    $xactions = array();

    $audit_phids = $adapter->getAuditMap();
    foreach ($audit_phids as $phid => $rule_ids) {
      foreach ($rule_ids as $rule_id) {
        $this->addAuditReason(
          $phid,
          pht(
            '%s Triggered Audit',
            "H{$rule_id}"));
      }
    }
    if ($audit_phids) {
      $xactions[] = id(new PhabricatorAuditTransaction())
        ->setTransactionType(PhabricatorAuditActionConstants::ADD_AUDITORS)
        ->setNewValue(array_fuse(array_keys($audit_phids)))
        ->setMetadataValue(
          'auditStatus',
          PhabricatorAuditStatusConstants::AUDIT_REQUIRED)
        ->setMetadataValue(
          'auditReasonMap', $this->auditReasonMap);
    }

    $cc_phids = $adapter->getAddCCMap();
    $add_ccs = array('+' => array());
    foreach ($cc_phids as $phid => $rule_ids) {
      $add_ccs['+'][$phid] = $phid;
    }
    $xactions[] = id(new PhabricatorAuditTransaction())
      ->setTransactionType(PhabricatorTransactions::TYPE_SUBSCRIBERS)
      ->setNewValue($add_ccs);

    $this->heraldEmailPHIDs = $adapter->getEmailPHIDs();

    HarbormasterBuildable::applyBuildPlans(
      $object->getPHID(),
      $object->getRepository()->getPHID(),
      $adapter->getBuildPlans());

    $limit = self::MAX_FILES_SHOWN_IN_EMAIL;
    $files = $adapter->loadAffectedPaths();
    sort($files);
    if (count($files) > $limit) {
      array_splice($files, $limit);
      $files[] = pht(
        '(This commit affected more than %d files. Only %d are shown here '.
        'and additional ones are truncated.)',
        $limit,
        $limit);
    }
    $this->affectedFiles = implode("\n", $files);

    return $xactions;
  }

  private function isCommitMostlyImported(PhabricatorLiskDAO $object) {
    $has_message = PhabricatorRepositoryCommit::IMPORTED_MESSAGE;
    $has_changes = PhabricatorRepositoryCommit::IMPORTED_CHANGE;

    // Don't publish feed stories or email about events which occur during
    // import. In particular, this affects tasks being attached when they are
    // closed by "Fixes Txxxx" in a commit message. See T5851.

    $mask = ($has_message | $has_changes);

    return $object->isPartiallyImported($mask);
  }

}
