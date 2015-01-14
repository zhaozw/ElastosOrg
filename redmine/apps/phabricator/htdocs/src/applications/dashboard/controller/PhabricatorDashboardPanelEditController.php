<?php

final class PhabricatorDashboardPanelEditController
  extends PhabricatorDashboardController {

  private $id;

  public function willProcessRequest(array $data) {
    $this->id = idx($data, 'id');
  }

  public function processRequest() {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    // If the user is trying to create a panel directly on a dashboard, make
    // sure they have permission to see and edit the dashboard.

    $dashboard_id = $request->getInt('dashboardID');
    $dashboard = null;
    if ($dashboard_id) {
      $dashboard = id(new PhabricatorDashboardQuery())
        ->setViewer($viewer)
        ->withIDs(array($dashboard_id))
        ->requireCapabilities(
          array(
            PhabricatorPolicyCapability::CAN_VIEW,
            PhabricatorPolicyCapability::CAN_EDIT,
          ))
        ->executeOne();
      if (!$dashboard) {
        return new Aphront404Response();
      }

      $manage_uri = $this->getApplicationURI('manage/'.$dashboard_id.'/');
    }

    if ($this->id) {
      $is_create = false;

      if ($dashboard) {
        $capabilities = array(
          PhabricatorPolicyCapability::CAN_VIEW,
        );
      } else {
        $capabilities = array(
          PhabricatorPolicyCapability::CAN_VIEW,
          PhabricatorPolicyCapability::CAN_EDIT,
        );
      }

      $panel = id(new PhabricatorDashboardPanelQuery())
        ->setViewer($viewer)
        ->withIDs(array($this->id))
        ->requireCapabilities($capabilities)
        ->executeOne();
      if (!$panel) {
        return new Aphront404Response();
      }

      if ($dashboard) {
        $can_edit = PhabricatorPolicyFilter::hasCapability(
          $viewer,
          $panel,
          PhabricatorPolicyCapability::CAN_EDIT);
        if (!$can_edit) {
          if ($request->isFormPost() && $request->getBool('copy')) {
            $panel = $this->copyPanel(
              $request,
              $dashboard,
              $panel);
          } else {
            return $this->processPanelCloneRequest(
              $request,
              $dashboard,
              $panel);
          }
        }
      }
    } else {
      $is_create = true;

      $panel = PhabricatorDashboardPanel::initializeNewPanel($viewer);
      $types = PhabricatorDashboardPanelType::getAllPanelTypes();
      $type = $request->getStr('type');
      if (empty($types[$type])) {
        return $this->processPanelTypeRequest($request);
      }

      $panel->setPanelType($type);
    }

    if ($is_create) {
      $title = pht('New Panel');
      $header = pht('Create New Panel');
      $button = pht('Create Panel');
      if ($dashboard) {
        $cancel_uri = $manage_uri;
      } else {
        $cancel_uri = $this->getApplicationURI('panel/');
      }
    } else {
      $title = pht('Edit %s', $panel->getMonogram());
      $header = pht('Edit %s %s', $panel->getMonogram(), $panel->getName());
      $button = pht('Save Panel');
      if ($dashboard) {
        $cancel_uri = $manage_uri;
      } else {
        $cancel_uri = '/'.$panel->getMonogram();
      }
    }

    $v_name = $panel->getName();
    $e_name = true;

    $field_list = PhabricatorCustomField::getObjectFields(
      $panel,
      PhabricatorCustomField::ROLE_EDIT);
    $field_list
      ->setViewer($viewer)
      ->readFieldsFromStorage($panel);

    if ($is_create && !$request->isFormPost()) {
      $panel->requireImplementation()->initializeFieldsFromRequest(
        $panel,
        $field_list,
        $request);
    }

    $validation_exception = null;

    // NOTE: We require 'edit' to distinguish between the "Choose a Type"
    // and "Create a Panel" dialogs.

    if ($request->isFormPost() && $request->getBool('edit')) {
      $v_name = $request->getStr('name');
      $v_view_policy = $request->getStr('viewPolicy');
      $v_edit_policy = $request->getStr('editPolicy');

      $type_name = PhabricatorDashboardPanelTransaction::TYPE_NAME;
      $type_view_policy = PhabricatorTransactions::TYPE_VIEW_POLICY;
      $type_edit_policy = PhabricatorTransactions::TYPE_EDIT_POLICY;

      $xactions = array();

      $xactions[] = id(new PhabricatorDashboardPanelTransaction())
        ->setTransactionType($type_name)
        ->setNewValue($v_name);

      $xactions[] = id(new PhabricatorDashboardPanelTransaction())
        ->setTransactionType($type_view_policy)
        ->setNewValue($v_view_policy);

      $xactions[] = id(new PhabricatorDashboardPanelTransaction())
        ->setTransactionType($type_edit_policy)
        ->setNewValue($v_edit_policy);

      $field_xactions = $field_list->buildFieldTransactionsFromRequest(
        new PhabricatorDashboardPanelTransaction(),
        $request);
      $xactions = array_merge($xactions, $field_xactions);

      try {
        $editor = id(new PhabricatorDashboardPanelTransactionEditor())
          ->setActor($viewer)
          ->setContinueOnNoEffect(true)
          ->setContentSourceFromRequest($request)
          ->applyTransactions($panel, $xactions);

        // If we're creating a panel directly on a dashboard, add it now.
        if ($dashboard) {
          PhabricatorDashboardTransactionEditor::addPanelToDashboard(
            $viewer,
            PhabricatorContentSource::newFromRequest($request),
            $panel,
            $dashboard,
            $request->getInt('column', 0));
        }

        if ($dashboard) {
          $done_uri = $manage_uri;
        } else {
          $done_uri = '/'.$panel->getMonogram();
        }

        return id(new AphrontRedirectResponse())->setURI($done_uri);
      } catch (PhabricatorApplicationTransactionValidationException $ex) {
        $validation_exception = $ex;

        $e_name = $validation_exception->getShortMessage($type_name);

        $panel->setViewPolicy($v_view_policy);
        $panel->setEditPolicy($v_edit_policy);
      }
    }

    // NOTE: We're setting the submit URI explicitly because we need to edit
    // a different panel if we just cloned the original panel.
    if ($is_create) {
      $submit_uri = $this->getApplicationURI('panel/edit/');
    } else {
      $submit_uri = $this->getApplicationURI('panel/edit/'.$panel->getID().'/');
    }

    $policies = id(new PhabricatorPolicyQuery())
      ->setViewer($viewer)
      ->setObject($panel)
      ->execute();

    $form = id(new AphrontFormView())
      ->setUser($viewer)
      ->setAction($submit_uri)
      ->addHiddenInput('edit', true)
      ->addHiddenInput('dashboardID', $request->getInt('dashboardID'))
      ->addHiddenInput('column', $request->getInt('column'))
      ->appendChild(
        id(new AphrontFormTextControl())
          ->setLabel(pht('Name'))
          ->setName('name')
          ->setValue($v_name)
          ->setError($e_name))
      ->appendChild(
        id(new AphrontFormPolicyControl())
          ->setName('viewPolicy')
          ->setPolicyObject($panel)
          ->setCapability(PhabricatorPolicyCapability::CAN_VIEW)
          ->setPolicies($policies))
      ->appendChild(
        id(new AphrontFormPolicyControl())
          ->setName('editPolicy')
          ->setPolicyObject($panel)
          ->setCapability(PhabricatorPolicyCapability::CAN_EDIT)
          ->setPolicies($policies));

    $field_list->appendFieldsToForm($form);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(
      pht('Panels'),
      $this->getApplicationURI('panel/'));
    if ($is_create) {
      $crumbs->addTextCrumb(pht('New Panel'));
      $form->addHiddenInput('type', $panel->getPanelType());
    } else {
      $crumbs->addTextCrumb(
        $panel->getMonogram(),
        '/'.$panel->getMonogram());
      $crumbs->addTextCrumb(pht('Edit'));
    }

    if ($request->isAjax()) {
      return $this->newDialog()
        ->setTitle($header)
        ->setSubmitURI($submit_uri)
        ->setWidth(AphrontDialogView::WIDTH_FORM)
        ->setValidationException($validation_exception)
        ->appendChild($form->buildLayoutView())
        ->addCancelButton($cancel_uri)
        ->addSubmitButton($button);
    } else {
      $form
        ->appendChild(
          id(new AphrontFormSubmitControl())
            ->setValue($button)
            ->addCancelButton($cancel_uri));
    }

    $box = id(new PHUIObjectBoxView())
      ->setHeaderText($header)
      ->setValidationException($validation_exception)
      ->setForm($form);

    return $this->buildApplicationPage(
      array(
        $crumbs,
        $box,
      ),
      array(
        'title' => $title,
      ));
  }

  private function processPanelTypeRequest(AphrontRequest $request) {
    $viewer = $request->getUser();

    $types = PhabricatorDashboardPanelType::getAllPanelTypes();

    $v_type = null;
    $errors = array();
    if ($request->isFormPost()) {
      $v_type = $request->getStr('type');
      if (!isset($types[$v_type])) {
        $errors[] = pht('You must select a type of panel to create.');
      }
    }

    $cancel_uri = $this->getApplicationURI('panel/');

    if (!$v_type) {
      $v_type = key($types);
    }

    $panel_types = id(new AphrontFormRadioButtonControl())
      ->setName('type')
      ->setValue($v_type);

    foreach ($types as $key => $type) {
      $panel_types->addButton(
        $key,
        $type->getPanelTypeName(),
        $type->getPanelTypeDescription());
    }

    $form = id(new AphrontFormView())
      ->setUser($viewer)
      ->addHiddenInput('dashboardID', $request->getInt('dashboardID'))
      ->addHiddenInput('column', $request->getInt('column'))
      ->appendRemarkupInstructions(
        pht(
          'Choose the type of dashboard panel to create:'))
      ->appendChild($panel_types);

    if ($request->isAjax()) {
      return $this->newDialog()
        ->setTitle(pht('Add New Panel'))
        ->setWidth(AphrontDialogView::WIDTH_FORM)
        ->setErrors($errors)
        ->appendChild($form->buildLayoutView())
        ->addCancelbutton($cancel_uri)
        ->addSubmitButton(pht('Continue'));
    } else {
      $form->appendChild(
        id(new AphrontFormSubmitControl())
          ->setValue(pht('Continue'))
          ->addCancelButton($cancel_uri));
    }

    $title = pht('Create Dashboard Panel');

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(
      pht('Panels'),
      $this->getApplicationURI('panel/'));
    $crumbs->addTextCrumb(pht('New Panel'));

    $box = id(new PHUIObjectBoxView())
      ->setHeaderText($title)
      ->setFormErrors($errors)
      ->setForm($form);

    return $this->buildApplicationPage(
      array(
        $crumbs,
        $box,
      ),
      array(
        'title' => $title,
      ));
  }

  private function processPanelCloneRequest(
    AphrontRequest $request,
    PhabricatorDashboard $dashboard,
    PhabricatorDashboardPanel $panel) {

    $viewer = $request->getUser();

    $manage_uri = $this->getApplicationURI('manage/'.$dashboard->getID().'/');

    return $this->newDialog()
      ->setTitle(pht('Copy Panel?'))
      ->addHiddenInput('copy', true)
      ->addHiddenInput('dashboardID', $request->getInt('dashboardID'))
      ->addHiddenInput('column', $request->getInt('column'))
      ->appendParagraph(
        pht(
          'You do not have permission to edit this dashboard panel, but you '.
          'can make a copy and edit that instead. If you choose to copy the '.
          'panel, the original will be replaced with the new copy on this '.
          'dashboard.'))
      ->appendParagraph(
        pht(
          'Do you want to make a copy of this panel?'))
      ->addCancelButton($manage_uri)
      ->addSubmitButton(pht('Copy Panel'));
  }

  private function copyPanel(
    AphrontRequest $request,
    PhabricatorDashboard $dashboard,
    PhabricatorDashboardPanel $panel) {

    $viewer = $request->getUser();

    $copy = PhabricatorDashboardPanel::initializeNewPanel($viewer);
    $copy = PhabricatorDashboardPanel::copyPanel($copy, $panel);

    $copy->openTransaction();
      $copy->save();

      // TODO: This should record a transaction on the panel copy, too.

      $xactions = array();
      $xactions[] = id(new PhabricatorDashboardTransaction())
        ->setTransactionType(PhabricatorTransactions::TYPE_EDGE)
        ->setMetadataValue(
          'edge:type',
          PhabricatorEdgeConfig::TYPE_DASHBOARD_HAS_PANEL)
        ->setNewValue(
          array(
            '+' => array(
              $copy->getPHID() => $copy->getPHID(),
            ),
            '-' => array(
              $panel->getPHID() => $panel->getPHID(),
            ),
          ));

      $layout_config = $dashboard->getLayoutConfigObject();
      $layout_config->replacePanel($panel->getPHID(), $copy->getPHID());
      $dashboard->setLayoutConfigFromObject($layout_config);
      $dashboard->save();

      $editor = id(new PhabricatorDashboardTransactionEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnMissingFields(true)
        ->setContinueOnNoEffect(true)
        ->applyTransactions($dashboard, $xactions);
    $copy->saveTransaction();

    return $copy;
  }


}
