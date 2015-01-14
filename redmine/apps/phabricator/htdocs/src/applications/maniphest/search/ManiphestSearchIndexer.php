<?php

final class ManiphestSearchIndexer extends PhabricatorSearchDocumentIndexer {

  public function getIndexableObject() {
    return new ManiphestTask();
  }

  protected function buildAbstractDocumentByPHID($phid) {
    $task = $this->loadDocumentByPHID($phid);

    $doc = new PhabricatorSearchAbstractDocument();
    $doc->setPHID($task->getPHID());
    $doc->setDocumentType(ManiphestTaskPHIDType::TYPECONST);
    $doc->setDocumentTitle($task->getTitle());
    $doc->setDocumentCreated($task->getDateCreated());
    $doc->setDocumentModified($task->getDateModified());

    $doc->addField(
      PhabricatorSearchField::FIELD_BODY,
      $task->getDescription());

    $doc->addRelationship(
      PhabricatorSearchRelationship::RELATIONSHIP_AUTHOR,
      $task->getAuthorPHID(),
      PhabricatorPeopleUserPHIDType::TYPECONST,
      $task->getDateCreated());

    $doc->addRelationship(
      $task->isClosed()
        ? PhabricatorSearchRelationship::RELATIONSHIP_CLOSED
        : PhabricatorSearchRelationship::RELATIONSHIP_OPEN,
      $task->getPHID(),
      ManiphestTaskPHIDType::TYPECONST,
      time());

    $this->indexTransactions(
      $doc,
      new ManiphestTransactionQuery(),
      array($phid));

    $owner = $task->getOwnerPHID();
    if ($owner) {
      $doc->addRelationship(
        PhabricatorSearchRelationship::RELATIONSHIP_OWNER,
        $owner,
        PhabricatorPeopleUserPHIDType::TYPECONST,
        time());
    } else {
      $doc->addRelationship(
        PhabricatorSearchRelationship::RELATIONSHIP_UNOWNED,
        $task->getPHID(),
        PhabricatorPHIDConstants::PHID_TYPE_VOID,
        $task->getDateCreated());
    }

    // We need to load handles here since non-users may subscribe (mailing
    // lists, e.g.)
    $ccs = $task->getCCPHIDs();
    $handles = id(new PhabricatorHandleQuery())
      ->setViewer(PhabricatorUser::getOmnipotentUser())
      ->withPHIDs($ccs)
      ->execute();
    foreach ($ccs as $cc) {
      $doc->addRelationship(
        PhabricatorSearchRelationship::RELATIONSHIP_SUBSCRIBER,
        $handles[$cc]->getPHID(),
        $handles[$cc]->getType(),
        time());
    }

    return $doc;
  }

}
