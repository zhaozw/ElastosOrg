<?php

final class HarbormasterBuildStep extends HarbormasterDAO
  implements
    PhabricatorPolicyInterface,
    PhabricatorCustomFieldInterface {

  protected $name;
  protected $description;
  protected $buildPlanPHID;
  protected $className;
  protected $details = array();
  protected $sequence = 0;

  private $buildPlan = self::ATTACHABLE;
  private $customFields = self::ATTACHABLE;
  private $implementation;

  public static function initializeNewStep(PhabricatorUser $actor) {
    return id(new HarbormasterBuildStep());
  }

  public function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'details' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'className' => 'text255',
        'sequence' => 'uint32',
        'description' => 'text',

        // T6203/NULLABILITY
        // This should not be nullable. Current `null` values indicate steps
        // which predated editable names. These should be backfilled with
        // default names, then the code for handling `null` shoudl be removed.
        'name' => 'text255?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_plan' => array(
          'columns' => array('buildPlanPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhabricatorPHID::generateNewPHID(
      HarbormasterBuildStepPHIDType::TYPECONST);
  }

  public function attachBuildPlan(HarbormasterBuildPlan $plan) {
    $this->buildPlan = $plan;
    return $this;
  }

  public function getBuildPlan() {
    return $this->assertAttached($this->buildPlan);
  }

  public function getDetail($key, $default = null) {
    return idx($this->details, $key, $default);
  }

  public function setDetail($key, $value) {
    $this->details[$key] = $value;
    return $this;
  }

  public function getName() {
    if (strlen($this->name)) {
      return $this->name;
    }

    return $this->getStepImplementation()->getName();
  }

  public function getStepImplementation() {
    if ($this->implementation === null) {
      $obj = HarbormasterBuildStepImplementation::requireImplementation(
        $this->className);
      $obj->loadSettings($this);
      $this->implementation = $obj;
    }

    return $this->implementation;
  }


/* -(  PhabricatorPolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhabricatorPolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    return $this->getBuildPlan()->getPolicy($capability);
  }

  public function hasAutomaticCapability($capability, PhabricatorUser $viewer) {
    return $this->getBuildPlan()->hasAutomaticCapability($capability, $viewer);
  }

  public function describeAutomaticCapability($capability) {
    return pht('A build step has the same policies as its build plan.');
  }


/* -(  PhabricatorCustomFieldInterface  )------------------------------------ */


  public function getCustomFieldSpecificationForRole($role) {
    return array();
  }

  public function getCustomFieldBaseClass() {
    return 'HarbormasterBuildStepCustomField';
  }

  public function getCustomFields() {
    return $this->assertAttached($this->customFields);
  }

  public function attachCustomFields(PhabricatorCustomFieldAttachment $fields) {
    $this->customFields = $fields;
    return $this;
  }


}
