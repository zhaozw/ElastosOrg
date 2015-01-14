<?php

final class ManiphestTask extends ManiphestDAO
  implements
    PhabricatorMarkupInterface,
    PhabricatorPolicyInterface,
    PhabricatorTokenReceiverInterface,
    PhabricatorFlaggableInterface,
    PhabricatorMentionableInterface,
    PhrequentTrackableInterface,
    PhabricatorCustomFieldInterface,
    PhabricatorDestructibleInterface,
    PhabricatorApplicationTransactionInterface,
    PhabricatorProjectInterface {

  const MARKUP_FIELD_DESCRIPTION = 'markup:desc';

  protected $authorPHID;
  protected $ownerPHID;
  protected $ccPHIDs = array();

  protected $status;
  protected $priority;
  protected $subpriority = 0;

  protected $title = '';
  protected $originalTitle = '';
  protected $description = '';
  protected $originalEmailSource;
  protected $mailKey;
  protected $viewPolicy = PhabricatorPolicies::POLICY_USER;
  protected $editPolicy = PhabricatorPolicies::POLICY_USER;

  protected $attached = array();
  protected $projectPHIDs = array();
  private $subscribersNeedUpdate;

  protected $ownerOrdering;

  private $groupByProjectPHID = self::ATTACHABLE;
  private $customFields = self::ATTACHABLE;
  private $edgeProjectPHIDs = self::ATTACHABLE;

  public static function initializeNewTask(PhabricatorUser $actor) {
    $app = id(new PhabricatorApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhabricatorManiphestApplication'))
      ->executeOne();

    $view_policy = $app->getPolicy(ManiphestDefaultViewCapability::CAPABILITY);
    $edit_policy = $app->getPolicy(ManiphestDefaultEditCapability::CAPABILITY);

    return id(new ManiphestTask())
      ->setStatus(ManiphestTaskStatus::getDefaultStatus())
      ->setPriority(ManiphestTaskPriority::getDefaultPriority())
      ->setAuthorPHID($actor->getPHID())
      ->setViewPolicy($view_policy)
      ->setEditPolicy($edit_policy)
      ->attachProjectPHIDs(array());
  }

  public function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'ccPHIDs' => self::SERIALIZATION_JSON,
        'attached' => self::SERIALIZATION_JSON,
        'projectPHIDs' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'ownerPHID' => 'phid?',
        'status' => 'text12',
        'priority' => 'uint32',
        'title' => 'sort',
        'originalTitle' => 'text',
        'description' => 'text',
        'mailKey' => 'bytes20',
        'ownerOrdering' => 'text64?',
        'originalEmailSource' => 'text255?',
        'subpriority' => 'double',

        // T6203/NULLABILITY
        // This should not be nullable. It's going away soon anyway.
        'ccPHIDs' => 'text?',

      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_phid' => null,
        'phid' => array(
          'columns' => array('phid'),
          'unique' => true,
        ),
        'priority' => array(
          'columns' => array('priority', 'status'),
        ),
        'status' => array(
          'columns' => array('status'),
        ),
        'ownerPHID' => array(
          'columns' => array('ownerPHID', 'status'),
        ),
        'authorPHID' => array(
          'columns' => array('authorPHID', 'status'),
        ),
        'ownerOrdering' => array(
          'columns' => array('ownerOrdering'),
        ),
        'priority_2' => array(
          'columns' => array('priority', 'subpriority'),
        ),
        'key_dateCreated' => array(
          'columns' => array('dateCreated'),
        ),
        'key_dateModified' => array(
          'columns' => array('dateModified'),
        ),
        'key_title' => array(
          'columns' => array('title(64)'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function loadDependsOnTaskPHIDs() {
    return PhabricatorEdgeQuery::loadDestinationPHIDs(
      $this->getPHID(),
      PhabricatorEdgeConfig::TYPE_TASK_DEPENDS_ON_TASK);
  }

  public function loadDependedOnByTaskPHIDs() {
    return PhabricatorEdgeQuery::loadDestinationPHIDs(
      $this->getPHID(),
      PhabricatorEdgeConfig::TYPE_TASK_DEPENDED_ON_BY_TASK);
  }

  public function getAttachedPHIDs($type) {
    return array_keys(idx($this->attached, $type, array()));
  }

  public function generatePHID() {
    return PhabricatorPHID::generateNewPHID(ManiphestTaskPHIDType::TYPECONST);
  }

  public function getCCPHIDs() {
    return array_values(nonempty($this->ccPHIDs, array()));
  }

  public function getProjectPHIDs() {
    return $this->assertAttached($this->edgeProjectPHIDs);
  }

  public function attachProjectPHIDs(array $phids) {
    $this->edgeProjectPHIDs = $phids;
    return $this;
  }

  public function setCCPHIDs(array $phids) {
    $this->ccPHIDs = array_values($phids);
    $this->subscribersNeedUpdate = true;
    return $this;
  }

  public function setOwnerPHID($phid) {
    $this->ownerPHID = nonempty($phid, null);
    $this->subscribersNeedUpdate = true;
    return $this;
  }

  public function setTitle($title) {
    $this->title = $title;
    if (!$this->getID()) {
      $this->originalTitle = $title;
    }
    return $this;
  }

  public function getMonogram() {
    return 'T'.$this->getID();
  }

  public function attachGroupByProjectPHID($phid) {
    $this->groupByProjectPHID = $phid;
    return $this;
  }

  public function getGroupByProjectPHID() {
    return $this->assertAttached($this->groupByProjectPHID);
  }

  public function save() {
    if (!$this->mailKey) {
      $this->mailKey = Filesystem::readRandomCharacters(20);
    }

    $result = parent::save();

    if ($this->subscribersNeedUpdate) {
      // If we've changed the subscriber PHIDs for this task, update the link
      // table.
      ManiphestTaskSubscriber::updateTaskSubscribers($this);
      $this->subscribersNeedUpdate = false;
    }

    return $result;
  }

  public function isClosed() {
    return ManiphestTaskStatus::isClosedStatus($this->getStatus());
  }

  public function getPrioritySortVector() {
    return array(
      $this->getPriority(),
      -$this->getSubpriority(),
      $this->getID(),
    );
  }


/* -(  Markup Interface  )--------------------------------------------------- */


  /**
   * @task markup
   */
  public function getMarkupFieldKey($field) {
    $hash = PhabricatorHash::digest($this->getMarkupText($field));
    $id = $this->getID();
    return "maniphest:T{$id}:{$field}:{$hash}";
  }


  /**
   * @task markup
   */
  public function getMarkupText($field) {
    return $this->getDescription();
  }


  /**
   * @task markup
   */
  public function newMarkupEngine($field) {
    return PhabricatorMarkupEngine::newManiphestMarkupEngine();
  }


  /**
   * @task markup
   */
  public function didMarkupText(
    $field,
    $output,
    PhutilMarkupEngine $engine) {
    return $output;
  }


  /**
   * @task markup
   */
  public function shouldUseMarkupCache($field) {
    return (bool)$this->getID();
  }


/* -(  Policy Interface  )--------------------------------------------------- */


  public function getCapabilities() {
    return array(
      PhabricatorPolicyCapability::CAN_VIEW,
      PhabricatorPolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhabricatorPolicyCapability::CAN_VIEW:
        return $this->getViewPolicy();
      case PhabricatorPolicyCapability::CAN_EDIT:
        return $this->getEditPolicy();
    }
  }

  public function hasAutomaticCapability($capability, PhabricatorUser $user) {
    // The owner of a task can always view and edit it.
    $owner_phid = $this->getOwnerPHID();
    if ($owner_phid) {
      $user_phid = $user->getPHID();
      if ($user_phid == $owner_phid) {
        return true;
      }
    }

    return false;
  }

  public function describeAutomaticCapability($capability) {
    return pht('The owner of a task can always view and edit it.');
  }


/* -(  PhabricatorTokenReceiverInterface  )---------------------------------- */


  public function getUsersToNotifyOfTokenGiven() {
    // Sort of ambiguous who this was intended for; just let them both know.
    return array_filter(
      array_unique(
        array(
          $this->getAuthorPHID(),
          $this->getOwnerPHID(),
        )));
  }


/* -(  PhabricatorCustomFieldInterface  )------------------------------------ */


  public function getCustomFieldSpecificationForRole($role) {
    return PhabricatorEnv::getEnvConfig('maniphest.fields');
  }

  public function getCustomFieldBaseClass() {
    return 'ManiphestCustomField';
  }

  public function getCustomFields() {
    return $this->assertAttached($this->customFields);
  }

  public function attachCustomFields(PhabricatorCustomFieldAttachment $fields) {
    $this->customFields = $fields;
    return $this;
  }


/* -(  PhabricatorDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhabricatorDestructionEngine $engine) {

    $this->openTransaction();

      // TODO: Once this implements PhabricatorTransactionInterface, this
      // will be handled automatically and can be removed.
      $xactions = id(new ManiphestTransaction())->loadAllWhere(
        'objectPHID = %s',
        $this->getPHID());
      foreach ($xactions as $xaction) {
        $engine->destroyObject($xaction);
      }

      $this->delete();
    $this->saveTransaction();
  }


/* -(  PhabricatorApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new ManiphestTransactionEditor();
  }

  public function getApplicationTransactionObject() {
    return $this;
  }

  public function getApplicationTransactionTemplate() {
    return new ManiphestTransaction();
  }

}
