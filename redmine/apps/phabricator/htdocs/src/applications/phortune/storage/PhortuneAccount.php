<?php

/**
 * An account represents a purchasing entity. An account may have multiple users
 * on it (e.g., several employees of a company have access to the company
 * account), and a user may have several accounts (e.g., a company account and
 * a personal account).
 */
final class PhortuneAccount extends PhortuneDAO
  implements PhabricatorPolicyInterface {

  protected $name;

  private $memberPHIDs = self::ATTACHABLE;

  public static function initializeNewAccount(PhabricatorUser $actor) {
    $account = id(new PhortuneAccount());

    $account->memberPHIDs = array();

    return $account;
  }

  public static function createNewAccount(
    PhabricatorUser $actor,
    PhabricatorContentSource $content_source) {

    $account = PhortuneAccount::initializeNewAccount($actor);

    $xactions = array();
    $xactions[] = id(new PhortuneAccountTransaction())
      ->setTransactionType(PhortuneAccountTransaction::TYPE_NAME)
      ->setNewValue(pht('Personal Account'));

    $xactions[] = id(new PhortuneAccountTransaction())
      ->setTransactionType(PhabricatorTransactions::TYPE_EDGE)
      ->setMetadataValue(
        'edge:type',
        PhortuneAccountHasMemberEdgeType::EDGECONST)
      ->setNewValue(
        array(
          '=' => array($actor->getPHID() => $actor->getPHID()),
        ));

    $editor = id(new PhortuneAccountEditor())
      ->setActor($actor)
      ->setContentSource($content_source);

    // We create an account for you the first time you visit Phortune.
    $unguarded = AphrontWriteGuard::beginScopedUnguardedWrites();

      $editor->applyTransactions($account, $xactions);

    unset($unguarded);

    return $account;
  }

  public function newCart(
    PhabricatorUser $actor,
    PhortuneCartImplementation $implementation,
    PhortuneMerchant $merchant) {

    $cart = PhortuneCart::initializeNewCart($actor, $this, $merchant);

    $cart->setCartClass(get_class($implementation));
    $cart->attachImplementation($implementation);

    $implementation->willCreateCart($actor, $cart);

    return $cart->save();
  }

  public function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text255',
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhabricatorPHID::generateNewPHID(
      PhortuneAccountPHIDType::TYPECONST);
  }

  public function getMemberPHIDs() {
    return $this->assertAttached($this->memberPHIDs);
  }

  public function attachMemberPHIDs(array $phids) {
    $this->memberPHIDs = $phids;
    return $this;
  }


/* -(  PhabricatorPolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhabricatorPolicyCapability::CAN_VIEW,
      PhabricatorPolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhabricatorPolicyCapability::CAN_VIEW:
        // Accounts are technically visible to all users, because merchant
        // controllers need to be able to see accounts in order to process
        // orders. We lock things down more tightly at the application level.
        return PhabricatorPolicies::POLICY_USER;
      case PhabricatorPolicyCapability::CAN_EDIT:
        if ($this->getPHID() === null) {
          // Allow a user to create an account for themselves.
          return PhabricatorPolicies::POLICY_USER;
        } else {
          return PhabricatorPolicies::POLICY_NOONE;
        }
    }
  }

  public function hasAutomaticCapability($capability, PhabricatorUser $viewer) {
    $members = array_fuse($this->getMemberPHIDs());
    return isset($members[$viewer->getPHID()]);
  }

  public function describeAutomaticCapability($capability) {
    return pht('Members of an account can always view and edit it.');
  }


}
