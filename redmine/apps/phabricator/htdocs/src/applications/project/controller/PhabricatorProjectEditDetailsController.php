<?php

final class PhabricatorProjectEditDetailsController
  extends PhabricatorProjectController {

  private $id;

  public function willProcessRequest(array $data) {
    $this->id = idx($data, 'id');
  }

  public function processRequest() {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    if ($this->id) {
      $is_new = false;

      $project = id(new PhabricatorProjectQuery())
        ->setViewer($viewer)
        ->withIDs(array($this->id))
        ->needSlugs(true)
        ->requireCapabilities(
          array(
            PhabricatorPolicyCapability::CAN_VIEW,
            PhabricatorPolicyCapability::CAN_EDIT,
          ))
          ->executeOne();
      if (!$project) {
        return new Aphront404Response();
      }

    } else {
      $is_new = true;

      $this->requireApplicationCapability(
        ProjectCreateProjectsCapability::CAPABILITY);

      $project = PhabricatorProject::initializeNewProject($viewer);
    }

    $field_list = PhabricatorCustomField::getObjectFields(
      $project,
      PhabricatorCustomField::ROLE_EDIT);
    $field_list
      ->setViewer($viewer)
      ->readFieldsFromStorage($project);

    $e_name = true;
    $e_slugs = false;
    $e_edit = null;

    $v_name = $project->getName();
    $project_slugs = $project->getSlugs();
    $project_slugs = mpull($project_slugs, 'getSlug', 'getSlug');
    $v_primary_slug = $project->getPrimarySlug();
    unset($project_slugs[$v_primary_slug]);
    $v_slugs = $project_slugs;
    $v_color = $project->getColor();
    $v_icon = $project->getIcon();
    $v_locked = $project->getIsMembershipLocked();

    $validation_exception = null;

    if ($request->isFormPost()) {
      $e_name = null;
      $e_slugs = null;

      $v_name = $request->getStr('name');
      $v_slugs = $request->getStrList('slugs');
      $v_view = $request->getStr('can_view');
      $v_edit = $request->getStr('can_edit');
      $v_join = $request->getStr('can_join');
      $v_color = $request->getStr('color');
      $v_icon = $request->getStr('icon');
      $v_locked = $request->getInt('is_membership_locked', 0);

      $xactions = $field_list->buildFieldTransactionsFromRequest(
        new PhabricatorProjectTransaction(),
        $request);

      $type_name = PhabricatorProjectTransaction::TYPE_NAME;
      $type_slugs = PhabricatorProjectTransaction::TYPE_SLUGS;
      $type_edit = PhabricatorTransactions::TYPE_EDIT_POLICY;
      $type_icon = PhabricatorProjectTransaction::TYPE_ICON;
      $type_color = PhabricatorProjectTransaction::TYPE_COLOR;
      $type_locked = PhabricatorProjectTransaction::TYPE_LOCKED;

      $xactions[] = id(new PhabricatorProjectTransaction())
        ->setTransactionType($type_name)
        ->setNewValue($v_name);

      $xactions[] = id(new PhabricatorProjectTransaction())
        ->setTransactionType($type_slugs)
        ->setNewValue($v_slugs);

      $xactions[] = id(new PhabricatorProjectTransaction())
        ->setTransactionType(PhabricatorTransactions::TYPE_VIEW_POLICY)
        ->setNewValue($v_view);

      $xactions[] = id(new PhabricatorProjectTransaction())
        ->setTransactionType($type_edit)
        ->setNewValue($v_edit);

      $xactions[] = id(new PhabricatorProjectTransaction())
        ->setTransactionType(PhabricatorTransactions::TYPE_JOIN_POLICY)
        ->setNewValue($v_join);

      $xactions[] = id(new PhabricatorProjectTransaction())
        ->setTransactionType($type_icon)
        ->setNewValue($v_icon);

      $xactions[] = id(new PhabricatorProjectTransaction())
        ->setTransactionType($type_color)
        ->setNewValue($v_color);

      $xactions[] = id(new PhabricatorProjectTransaction())
        ->setTransactionType($type_locked)
        ->setNewValue($v_locked);

      $editor = id(new PhabricatorProjectTransactionEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true);

      if ($is_new) {
        $xactions[] = id(new PhabricatorProjectTransaction())
          ->setTransactionType(PhabricatorTransactions::TYPE_EDGE)
          ->setMetadataValue(
            'edge:type',
            PhabricatorEdgeConfig::TYPE_PROJ_MEMBER)
          ->setNewValue(
            array(
              '+' => array($viewer->getPHID() => $viewer->getPHID()),
            ));
      }

      try {

        $editor->applyTransactions($project, $xactions);

        if ($request->isAjax()) {
          return id(new AphrontAjaxResponse())
            ->setContent(array(
              'phid' => $project->getPHID(),
              'name' => $project->getName(),
            ));
        }

        if ($is_new) {
          $redirect_uri =
            $this->getApplicationURI('view/'.$project->getID().'/');
        } else {
          $redirect_uri =
            $this->getApplicationURI('edit/'.$project->getID().'/');
        }

        return id(new AphrontRedirectResponse())->setURI($redirect_uri);
      } catch (PhabricatorApplicationTransactionValidationException $ex) {
        $validation_exception = $ex;

        $e_name = $ex->getShortMessage($type_name);
        $e_slugs = $ex->getShortMessage($type_slugs);
        $e_edit = $ex->getShortMessage($type_edit);

        $project->setViewPolicy($v_view);
        $project->setEditPolicy($v_edit);
        $project->setJoinPolicy($v_join);
      }
    }

    if ($is_new) {
      $header_name = pht('Create a New Project');
      $title = pht('Create Project');
    } else {
      $header_name = pht('Edit Project');
      $title = pht('Edit Project');
    }

    $policies = id(new PhabricatorPolicyQuery())
      ->setViewer($viewer)
      ->setObject($project)
      ->execute();
    $v_slugs = implode(', ', $v_slugs);

    $form = id(new AphrontFormView())
      ->setUser($viewer)
      ->appendChild(
        id(new AphrontFormTextControl())
          ->setLabel(pht('Name'))
          ->setName('name')
          ->setValue($v_name)
          ->setError($e_name));
    $field_list->appendFieldsToForm($form);

    $shades = PhabricatorProjectIcon::getColorMap();

    if ($is_new) {
      $icon_uri = $this->getApplicationURI('icon/');
    } else {
      $icon_uri = $this->getApplicationURI('icon/'.$project->getID().'/');
    }
    $icon_display = PhabricatorProjectIcon::renderIconForChooser($v_icon);
    list($can_lock, $lock_message) = $this->explainApplicationCapability(
      ProjectCanLockProjectsCapability::CAPABILITY,
      pht('You can update the Lock Project setting.'),
      pht('You can not update the Lock Project setting.'));

    $form
      ->appendChild(
        id(new AphrontFormChooseButtonControl())
          ->setLabel(pht('Icon'))
          ->setName('icon')
          ->setDisplayValue($icon_display)
          ->setButtonText(pht('Choose Icon...'))
          ->setChooseURI($icon_uri)
          ->setValue($v_icon))
      ->appendChild(
        id(new AphrontFormSelectControl())
          ->setLabel(pht('Color'))
          ->setName('color')
          ->setValue($v_color)
          ->setOptions($shades))
      ->appendChild(
        id(new AphrontFormStaticControl())
        ->setLabel(pht('Primary Hashtag'))
        ->setCaption(pht('The primary hashtag is derived from the name.'))
        ->setValue($v_primary_slug))
      ->appendChild(
        id(new AphrontFormTextControl())
          ->setLabel(pht('Additional Hashtags'))
          ->setCaption(pht(
            'Specify a comma-separated list of additional hashtags.'))
          ->setName('slugs')
          ->setValue($v_slugs)
          ->setError($e_slugs))
      ->appendChild(
        id(new AphrontFormPolicyControl())
          ->setUser($viewer)
          ->setName('can_view')
          ->setPolicyObject($project)
          ->setPolicies($policies)
          ->setCapability(PhabricatorPolicyCapability::CAN_VIEW))
      ->appendChild(
        id(new AphrontFormPolicyControl())
          ->setUser($viewer)
          ->setName('can_edit')
          ->setPolicyObject($project)
          ->setPolicies($policies)
          ->setCapability(PhabricatorPolicyCapability::CAN_EDIT)
          ->setError($e_edit))
      ->appendChild(
        id(new AphrontFormPolicyControl())
          ->setUser($viewer)
          ->setName('can_join')
          ->setCaption(
            pht('Users who can edit a project can always join a project.'))
          ->setPolicyObject($project)
          ->setPolicies($policies)
          ->setCapability(PhabricatorPolicyCapability::CAN_JOIN))
      ->appendChild(
        id(new AphrontFormCheckboxControl())
        ->setLabel(pht('Lock Project'))
        ->setDisabled(!$can_lock)
        ->addCheckbox(
          'is_membership_locked',
          1,
          pht('Prevent members from leaving this project.'),
          $v_locked)
        ->setCaption($lock_message));

    if ($request->isAjax()) {
      $errors = array();
      if ($validation_exception) {
        $errors = mpull($ex->getErrors(), 'getMessage');
      }
      $dialog = id(new AphrontDialogView())
        ->setUser($viewer)
        ->setWidth(AphrontDialogView::WIDTH_FULL)
        ->setTitle($header_name)
        ->setErrors($errors)
        ->appendForm($form)
        ->addSubmitButton($title)
        ->addCancelButton('/project/');
      return id(new AphrontDialogResponse())->setDialog($dialog);
    }

    $form->appendChild(
      id(new AphrontFormSubmitControl())
      ->addCancelButton($this->getApplicationURI())
      ->setValue(pht('Save')));

    $form_box = id(new PHUIObjectBoxView())
      ->setHeaderText($title)
      ->setValidationException($validation_exception)
      ->setForm($form);

    $crumbs = $this->buildApplicationCrumbs($this->buildSideNavView());
    if ($is_new) {
      $crumbs->addTextCrumb($title);
    } else {
      $crumbs
        ->addTextCrumb($project->getName(),
          $this->getApplicationURI('view/'.$project->getID().'/'))
        ->addTextCrumb(pht('Edit'),
          $this->getApplicationURI('edit/'.$project->getID().'/'))
        ->addTextCrumb(pht('Details'));
    }

    return $this->buildApplicationPage(
      array(
        $crumbs,
        $form_box,
      ),
      array(
        'title' => $title,
      ));
  }
}
