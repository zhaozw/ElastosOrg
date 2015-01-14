<?php

final class PhabricatorMetaMTAApplication extends PhabricatorApplication {

  public function getName() {
    return pht('MetaMTA');
  }

  public function getIconName() {
    return 'metamta';
  }

  public function getShortDescription() {
    return pht('Delivers Mail');
  }

  public function getFlavorText() {
    return pht('Yo dawg, we heard you like MTAs.');
  }

  public function getApplicationGroup() {
    return self::GROUP_ADMIN;
  }

  public function canUninstall() {
    return false;
  }

  public function isLaunchable() {
    return false;
  }

  public function getTypeaheadURI() {
    return null;
  }

  public function getRoutes() {
    return array(
      '/mail/' => array(
        'sendgrid/' => 'PhabricatorMetaMTASendGridReceiveController',
        'mailgun/'  => 'PhabricatorMetaMTAMailgunReceiveController',
      ),
    );
  }

  public function getTitleGlyph() {
    return '@';
  }

}
