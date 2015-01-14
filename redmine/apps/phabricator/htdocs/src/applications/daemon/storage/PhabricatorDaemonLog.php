<?php

final class PhabricatorDaemonLog extends PhabricatorDaemonDAO
  implements PhabricatorPolicyInterface {

  const STATUS_UNKNOWN = 'unknown';
  const STATUS_RUNNING = 'run';
  const STATUS_DEAD    = 'dead';
  const STATUS_WAIT    = 'wait';
  const STATUS_EXITING  = 'exiting';
  const STATUS_EXITED  = 'exit';

  protected $daemon;
  protected $host;
  protected $pid;
  protected $argv;
  protected $explicitArgv = array();
  protected $envHash;
  protected $status;

  public function getConfiguration() {
    return array(
      self::CONFIG_SERIALIZATION => array(
        'argv' => self::SERIALIZATION_JSON,
        'explicitArgv' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'daemon' => 'text255',
        'host' => 'text255',
        'pid' => 'uint32',
        'envHash' => 'bytes40',
        'status' => 'text8',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'status' => array(
          'columns' => array('status'),
        ),
        'dateCreated' => array(
          'columns' => array('dateCreated'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getExplicitArgv() {
    $argv = $this->explicitArgv;
    if (!is_array($argv)) {
      return array();
    }
    return $argv;
  }


/* -(  PhabricatorPolicyInterface  )----------------------------------------- */

  public function getPHID() {
    return null;
  }

  public function getCapabilities() {
    return array(
      PhabricatorPolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    return PhabricatorPolicies::POLICY_ADMIN;
  }

  public function hasAutomaticCapability($capability, PhabricatorUser $viewer) {
    return false;
  }

  public function describeAutomaticCapability($capability) {
    return null;
  }

}
