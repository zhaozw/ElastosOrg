<?php

/**
 * @concrete-extensible
 */
class PhabricatorApplicationTransactionFeedStory
  extends PhabricatorFeedStory {

  public function getPrimaryObjectPHID() {
    return $this->getValue('objectPHID');
  }

  public function getRequiredObjectPHIDs() {
    return $this->getValue('transactionPHIDs');
  }

  public function getRequiredHandlePHIDs() {
    $phids = array();
    $phids[] = $this->getValue('objectPHID');
    foreach ($this->getValue('transactionPHIDs') as $xaction_phid) {
      $xaction = $this->getObject($xaction_phid);
      foreach ($xaction->getRequiredHandlePHIDs() as $handle_phid) {
        $phids[] = $handle_phid;
      }
    }
    return $phids;
  }

  protected function getPrimaryTransactionPHID() {
    return head($this->getValue('transactionPHIDs'));
  }

  public function getPrimaryTransaction() {
    return $this->getObject($this->getPrimaryTransactionPHID());
  }

  public function getFieldStoryMarkupFields() {
    $xaction_phids = $this->getValue('transactionPHIDs');

    $fields = array();
    foreach ($xaction_phids as $xaction_phid) {
      $xaction = $this->getObject($xaction_phid);
      foreach ($xaction->getMarkupFieldsForFeed($this) as $field) {
        $fields[] = $field;
      }
    }

    return $fields;
  }

  public function getMarkupText($field) {
    $xaction_phids = $this->getValue('transactionPHIDs');

    foreach ($xaction_phids as $xaction_phid) {
      $xaction = $this->getObject($xaction_phid);
      foreach ($xaction->getMarkupFieldsForFeed($this) as $xaction_field) {
        if ($xaction_field == $field) {
          return $xaction->getMarkupTextForFeed($this, $field);
        }
      }
    }

    return null;
  }

  public function renderView() {
    $view = $this->newStoryView();

    $handle = $this->getHandle($this->getPrimaryObjectPHID());
    $view->setHref($handle->getURI());

    $view->setAppIconFromPHID($handle->getPHID());

    $xaction_phids = $this->getValue('transactionPHIDs');
    $xaction = $this->getPrimaryTransaction();

    $xaction->setHandles($this->getHandles());
    $view->setTitle($xaction->getTitleForFeed($this));

    foreach ($xaction_phids as $xaction_phid) {
      $secondary_xaction = $this->getObject($xaction_phid);
      $secondary_xaction->setHandles($this->getHandles());

      $body = $secondary_xaction->getBodyForFeed($this);
      if (nonempty($body)) {
        $view->appendChild($body);
      }
    }

    $view->setImage(
      $this->getHandle($xaction->getAuthorPHID())->getImageURI());

    return $view;
  }

  public function renderText() {
    $xaction = $this->getPrimaryTransaction();
    $old_target = $xaction->getRenderingTarget();
    $new_target = PhabricatorApplicationTransaction::TARGET_TEXT;
    $xaction->setRenderingTarget($new_target);
    $xaction->setHandles($this->getHandles());
    $text = $xaction->getTitleForFeed($this);
    $xaction->setRenderingTarget($old_target);
    return $text;
  }

  public function renderAsTextForDoorkeeper(
    DoorkeeperFeedStoryPublisher $publisher) {

    $xactions = array();
    $xaction_phids = $this->getValue('transactionPHIDs');
    foreach ($xaction_phids as $xaction_phid) {
      $xaction = $this->getObject($xaction_phid);
      $xaction->setHandles($this->getHandles());
      $xactions[] = $xaction;
    }

    $primary = $this->getPrimaryTransaction();
    return $primary->renderAsTextForDoorkeeper($publisher, $this, $xactions);
  }

}
