<?php

final class PhabricatorDashboardUninstallController
  extends PhabricatorDashboardController {

  private $id;

  public function willProcessRequest(array $data) {
    $this->id = idx($data, 'id');
  }

  public function processRequest() {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    $dashboard = id(new PhabricatorDashboardQuery())
      ->setViewer($viewer)
      ->withIDs(array($this->id))
      ->executeOne();
    if (!$dashboard) {
      return new Aphront404Response();
    }
    $dashboard_phid = $dashboard->getPHID();

    $object_phid = $request->getStr('objectPHID', $viewer->getPHID());
    $object = id(new PhabricatorObjectQuery())
      ->setViewer($viewer)
      ->requireCapabilities(
        array(
          PhabricatorPolicyCapability::CAN_VIEW,
          PhabricatorPolicyCapability::CAN_EDIT,
        ))
      ->withPHIDs(array($object_phid))
      ->executeOne();
    if (!$object) {
      return new Aphront404Response();
    }

    $application_class = $request->getStr(
      'applicationClass',
      'PhabricatorHomeApplication');

    $dashboard_install = id(new PhabricatorDashboardInstall())
      ->loadOneWhere(
        'objectPHID = %s AND applicationClass = %s',
        $object_phid,
        $application_class);
    if (!$dashboard_install) {
      return new Aphront404Response();
    }
    if ($dashboard_install->getDashboardPHID() != $dashboard_phid) {
      return new Aphront404Response();
    }

    $installer_phid = $viewer->getPHID();
    $handles = $this->loadHandles(array($object_phid, $installer_phid));

    if ($request->isFormPost()) {
      $dashboard_install->delete();
      return id(new AphrontRedirectResponse())
        ->setURI($this->getRedirectURI($application_class, $object_phid));
    }

    $body = $this->getBodyContent(
      $application_class,
      $object_phid,
      $installer_phid);

    $form = id(new AphrontFormView())
      ->setUser($viewer)
      ->appendChild($body);

    return $this->newDialog()
      ->setTitle(pht('Uninstall Dashboard'))
      ->appendChild($form->buildLayoutView())
      ->addCancelButton($this->getCancelURI(
        $application_class, $object_phid))
      ->addSubmitButton(pht('Uninstall Dashboard'));
  }

  private function getBodyContent(
    $application_class,
    $object_phid,
    $installer_phid) {

    $body = array();
    switch ($application_class) {
      case 'PhabricatorHomeApplication':
        if ($installer_phid == $object_phid) {
          $body[] = phutil_tag(
            'p',
            array(),
            pht(
              'Are you sure you want to uninstall this dashboard as your '.
              'home page?'));
          $body[] = phutil_tag(
            'p',
            array(),
            pht(
              'You will be re-directed to your bland, default home page if '.
              'you choose to uninstall this dashboard.'));
        } else {
          $body[] = phutil_tag(
            'p',
            array(),
            pht(
              'Are you sure you want to uninstall this dashboard as the home '.
              'page for %s?',
              $this->getHandle($object_phid)->getName()));
        }
        break;
    }
    return $body;
  }

  private function getCancelURI($application_class, $object_phid) {
    $uri = null;
    switch ($application_class) {
      case 'PhabricatorHomeApplication':
        $uri = '/dashboard/view/'.$this->id.'/';
        break;
    }
    return $uri;
  }

  private function getRedirectURI($application_class, $object_phid) {
    $uri = null;
    switch ($application_class) {
      case 'PhabricatorHomeApplication':
        $uri = '/';
        break;
    }
    return $uri;
  }

}
