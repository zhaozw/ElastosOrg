<?php

final class PhabricatorApplicationUninstallController
  extends PhabricatorApplicationsController {

  private $application;
  private $action;

  public function shouldRequireAdmin() {
    return true;
  }

  public function willProcessRequest(array $data) {
    $this->application = $data['application'];
    $this->action = $data['action'];
  }

  public function processRequest() {
    $request = $this->getRequest();
    $user = $request->getUser();

    $selected = PhabricatorApplication::getByClass($this->application);

    if (!$selected) {
      return new Aphront404Response();
    }

    $view_uri = $this->getApplicationURI('view/'.$this->application);

    $prototypes_enabled = PhabricatorEnv::getEnvConfig(
      'phabricator.show-prototypes');

    $dialog = id(new AphrontDialogView())
               ->setUser($user)
               ->addCancelButton($view_uri);

    if ($selected->isPrototype() && !$prototypes_enabled) {
      $dialog
        ->setTitle(pht('Prototypes Not Enabled'))
        ->appendChild(
          pht(
            'To manage prototypes, enable them by setting %s in your '.
            'Phabricator configuration.',
            phutil_tag('tt', array(), 'phabricator.show-prototypes')));
      return id(new AphrontDialogResponse())->setDialog($dialog);
    }

    if ($request->isDialogFormPost()) {
      $this->manageApplication();
      return id(new AphrontRedirectResponse())->setURI($view_uri);
    }

    if ($this->action == 'install') {
      if ($selected->canUninstall()) {
        $dialog->setTitle('Confirmation')
               ->appendChild(
                 'Install '.$selected->getName().' application?')
               ->addSubmitButton('Install');

      } else {
        $dialog->setTitle('Information')
               ->appendChild('You cannot install an installed application.');
      }
    } else {
      if ($selected->canUninstall()) {
        $dialog->setTitle('Confirmation')
               ->appendChild(
                 'Really Uninstall '.$selected->getName().' application?')
               ->addSubmitButton('Uninstall');
      } else {
        $dialog->setTitle('Information')
               ->appendChild(
                 'This application cannot be uninstalled,
                 because it is required for Phabricator to work.');
      }
    }
    return id(new AphrontDialogResponse())->setDialog($dialog);
  }

  public function manageApplication() {
    $key = 'phabricator.uninstalled-applications';
    $config_entry = PhabricatorConfigEntry::loadConfigEntry($key);
    $list = $config_entry->getValue();
    $uninstalled = PhabricatorEnv::getEnvConfig($key);

    if (isset($uninstalled[$this->application])) {
      unset($list[$this->application]);
    } else {
      $list[$this->application] = true;
    }

    PhabricatorConfigEditor::storeNewValue(
      $this->getRequest()->getUser(),
      $config_entry,
      $list,
      PhabricatorContentSource::newFromRequest($this->getRequest()));
  }

}
