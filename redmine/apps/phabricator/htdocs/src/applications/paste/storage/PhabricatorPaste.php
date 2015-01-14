<?php

final class PhabricatorPaste extends PhabricatorPasteDAO
  implements
    PhabricatorSubscribableInterface,
    PhabricatorTokenReceiverInterface,
    PhabricatorFlaggableInterface,
    PhabricatorMentionableInterface,
    PhabricatorPolicyInterface,
    PhabricatorProjectInterface,
    PhabricatorDestructibleInterface,
    PhabricatorApplicationTransactionInterface {

  protected $title;
  protected $authorPHID;
  protected $filePHID;
  protected $language;
  protected $parentPHID;
  protected $viewPolicy;
  protected $mailKey;

  private $content = self::ATTACHABLE;
  private $rawContent = self::ATTACHABLE;

  public static function initializeNewPaste(PhabricatorUser $actor) {
    $app = id(new PhabricatorApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhabricatorPasteApplication'))
      ->executeOne();

    $view_policy = $app->getPolicy(PasteDefaultViewCapability::CAPABILITY);

    return id(new PhabricatorPaste())
      ->setTitle('')
      ->setAuthorPHID($actor->getPHID())
      ->setViewPolicy($view_policy);
  }

  public function getURI() {
    return '/P'.$this->getID();
  }

  public function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'title' => 'text255',
        'language' => 'text64',
        'mailKey' => 'bytes20',
        'parentPHID' => 'phid?',

        // T6203/NULLABILITY
        // Pastes should always have a view policy.
        'viewPolicy' => 'policy?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'parentPHID' => array(
          'columns' => array('parentPHID'),
        ),
        'authorPHID' => array(
          'columns' => array('authorPHID'),
        ),
        'key_dateCreated' => array(
          'columns' => array('dateCreated'),
        ),
        'key_language' => array(
          'columns' => array('language'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhabricatorPHID::generateNewPHID(
      PhabricatorPastePastePHIDType::TYPECONST);
  }

  public function save() {
    if (!$this->getMailKey()) {
      $this->setMailKey(Filesystem::readRandomCharacters(20));
    }
    return parent::save();
  }

  public function getFullName() {
    $title = $this->getTitle();
    if (!$title) {
      $title = pht('(An Untitled Masterwork)');
    }
    return 'P'.$this->getID().' '.$title;
  }

  public function getContent() {
    return $this->assertAttached($this->content);
  }

  public function attachContent($content) {
    $this->content = $content;
    return $this;
  }

  public function getRawContent() {
    return $this->assertAttached($this->rawContent);
  }

  public function attachRawContent($raw_content) {
    $this->rawContent = $raw_content;
    return $this;
  }

/* -(  PhabricatorSubscribableInterface  )----------------------------------- */


  public function isAutomaticallySubscribed($phid) {
    return ($this->authorPHID == $phid);
  }

  public function shouldShowSubscribersProperty() {
    return true;
  }

  public function shouldAllowSubscription($phid) {
    return true;
  }


/* -(  PhabricatorTokenReceiverInterface  )---------------------------------- */

  public function getUsersToNotifyOfTokenGiven() {
    return array(
      $this->getAuthorPHID(),
    );
  }


/* -(  PhabricatorPolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhabricatorPolicyCapability::CAN_VIEW,
      PhabricatorPolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    if ($capability == PhabricatorPolicyCapability::CAN_VIEW) {
      return $this->viewPolicy;
    }
    return PhabricatorPolicies::POLICY_NOONE;
  }

  public function hasAutomaticCapability($capability, PhabricatorUser $user) {
    return ($user->getPHID() == $this->getAuthorPHID());
  }

  public function describeAutomaticCapability($capability) {
    return pht('The author of a paste can always view and edit it.');
  }


/* -(  PhabricatorDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhabricatorDestructionEngine $engine) {

    if ($this->filePHID) {
      $file = id(new PhabricatorFileQuery())
        ->setViewer(PhabricatorUser::getOmnipotentUser())
        ->withPHIDs(array($this->filePHID))
        ->executeOne();
      if ($file) {
        $engine->destroyObject($file);
      }
    }

    $this->delete();
  }


/* -(  PhabricatorApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhabricatorPasteEditor();
  }

  public function getApplicationTransactionObject() {
    return $this;
  }

  public function getApplicationTransactionTemplate() {
    return new PhabricatorPasteTransaction();
  }

}
