<?php

abstract class PhabricatorConfigController extends PhabricatorController {

  public function shouldRequireAdmin() {
    return true;
  }

  public function buildSideNavView($filter = null, $for_app = false) {
    $user = $this->getRequest()->getUser();

    $nav = new AphrontSideNavFilterView();
    $nav->setBaseURI(new PhutilURI($this->getApplicationURI()));
    $nav->addLabel(pht('Configuration'));
    $nav->addFilter('/', pht('Browse Settings'));
    $nav->addFilter('all/', pht('All Settings'));
    $nav->addLabel(pht('Setup'));
    $nav->addFilter('issue/', pht('Setup Issues'));
    $nav->addLabel(pht('Database'));
    $nav->addFilter('database/', pht('Database Status'));
    $nav->addFilter('dbissue/', pht('Database Issues'));
    $nav->addLabel(pht('Welcome'));
    $nav->addFilter('welcome/', pht('Welcome Screen'));

    return $nav;
  }

  public function buildApplicationMenu() {
    return $this->buildSideNavView(null, true)->getMenu();
  }

}
