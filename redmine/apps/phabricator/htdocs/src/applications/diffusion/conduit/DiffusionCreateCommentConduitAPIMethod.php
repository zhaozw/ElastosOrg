<?php

final class DiffusionCreateCommentConduitAPIMethod
  extends DiffusionConduitAPIMethod {

  public function getAPIMethodName() {
    return 'diffusion.createcomment';
  }

  public function getMethodStatus() {
    return self::METHOD_STATUS_DEPRECATED;
  }

  public function getMethodDescription() {
    return 'Add a comment to a Diffusion commit. By specifying an action of '.
           '"concern", "accept", "resign", or "close", auditing actions can '.
           'be triggered. Defaults to "comment".';
  }

  public function defineParamTypes() {
    return array(
      'phid'    => 'required string',
      'action'  => 'optional string',
      'message' => 'required string',
      'silent'  => 'optional bool',
    );
  }

  public function defineReturnType() {
    return 'bool';
  }

  public function defineErrorTypes() {
    return array(
      'ERR_BAD_COMMIT' => 'No commit found with that PHID',
      'ERR_BAD_ACTION' => 'Invalid action type',
      'ERR_MISSING_MESSAGE' => 'Message is required',
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $commit_phid = $request->getValue('phid');
    $commit = id(new DiffusionCommitQuery())
      ->setViewer($request->getUser())
      ->withPHIDs(array($commit_phid))
      ->needAuditRequests(true)
      ->executeOne();
    if (!$commit) {
      throw new ConduitException('ERR_BAD_COMMIT');
    }

    $message = trim($request->getValue('message'));
    if (!$message) {
      throw new ConduitException('ERR_MISSING_MESSAGE');
    }

    $action = $request->getValue('action');
    if (!$action) {
      $action = PhabricatorAuditActionConstants::COMMENT;
    }

    // Disallow ADD_CCS, ADD_AUDITORS forever.
    if (!in_array($action, array(
      PhabricatorAuditActionConstants::CONCERN,
      PhabricatorAuditActionConstants::ACCEPT,
      PhabricatorAuditActionConstants::COMMENT,
      PhabricatorAuditActionConstants::RESIGN,
      PhabricatorAuditActionConstants::CLOSE,
    ))) {
      throw new ConduitException('ERR_BAD_ACTION');
    }

    $xactions = array();

    if ($action != PhabricatorAuditActionConstants::COMMENT) {
      $xactions[] = id(new PhabricatorAuditTransaction())
        ->setTransactionType(PhabricatorAuditActionConstants::ACTION)
        ->setNewValue($action);
    }

    if (strlen($message)) {
      $xactions[] = id(new PhabricatorAuditTransaction())
        ->setTransactionType(PhabricatorTransactions::TYPE_COMMENT)
        ->attachComment(
          id(new PhabricatorAuditTransactionComment())
            ->setCommitPHID($commit->getPHID())
            ->setContent($message));
    }

    id(new PhabricatorAuditEditor())
      ->setActor($request->getUser())
      ->setContentSourceFromConduitRequest($request)
      ->setDisableEmail($request->getValue('silent'))
      ->setContinueOnMissingFields(true)
      ->applyTransactions($commit, $xactions);

    return true;
  }

}
