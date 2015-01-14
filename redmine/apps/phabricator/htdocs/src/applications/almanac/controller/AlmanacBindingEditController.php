<?php

final class AlmanacBindingEditController
  extends AlmanacServiceController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $id = $request->getURIData('id');
    if ($id) {
      $binding = id(new AlmanacBindingQuery())
        ->setViewer($viewer)
        ->withIDs(array($id))
        ->requireCapabilities(
          array(
            PhabricatorPolicyCapability::CAN_VIEW,
            PhabricatorPolicyCapability::CAN_EDIT,
          ))
        ->executeOne();
      if (!$binding) {
        return new Aphront404Response();
      }

      $service = $binding->getService();
      $is_new = false;

      $service_uri = $service->getURI();
      $cancel_uri = $binding->getURI();
      $title = pht('Edit Binding');
      $save_button = pht('Save Changes');
    } else {
      $service = id(new AlmanacServiceQuery())
        ->setViewer($viewer)
        ->withIDs(array($request->getStr('serviceID')))
        ->requireCapabilities(
          array(
            PhabricatorPolicyCapability::CAN_VIEW,
            PhabricatorPolicyCapability::CAN_EDIT,
          ))
        ->executeOne();

      $binding = AlmanacBinding::initializeNewBinding($service);
      $is_new = true;

      $service_uri = $service->getURI();
      $cancel_uri = $service_uri;
      $title = pht('Create Binding');
      $save_button = pht('Create Binding');
    }

    $v_interface = array();
    if ($binding->getInterfacePHID()) {
      $v_interface = array($binding->getInterfacePHID());
    }
    $e_interface = true;

    $validation_exception = null;
    if ($request->isFormPost()) {
      $v_interface = $request->getArr('interfacePHIDs');

      $type_interface = AlmanacBindingTransaction::TYPE_INTERFACE;

      $xactions = array();

      $xactions[] = id(new AlmanacBindingTransaction())
        ->setTransactionType($type_interface)
        ->setNewValue(head($v_interface));

      $editor = id(new AlmanacBindingEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true);

      try {
        $editor->applyTransactions($binding, $xactions);

        $binding_uri = $binding->getURI();
        return id(new AphrontRedirectResponse())->setURI($binding_uri);
      } catch (PhabricatorApplicationTransactionValidationException $ex) {
        $validation_exception = $ex;
        $e_interface = $ex->getShortMessage($type_interface);
      }
    }

    $interface_handles = array();
    if ($v_interface) {
      $interface_handles = $this->loadViewerHandles($v_interface);
    }

    $form = id(new AphrontFormView())
      ->setUser($viewer)
      ->appendChild(
        id(new AphrontFormTokenizerControl())
          ->setName('interfacePHIDs')
          ->setLabel('Interface')
          ->setLimit(1)
          ->setDatasource(new AlmanacInterfaceDatasource())
          ->setValue($interface_handles)
          ->setError($e_interface))
      ->appendChild(
        id(new AphrontFormSubmitControl())
          ->addCancelButton($cancel_uri)
          ->setValue($save_button));

    $box = id(new PHUIObjectBoxView())
      ->setValidationException($validation_exception)
      ->setHeaderText($title)
      ->appendChild($form);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb($service->getName(), $service_uri);
    if ($is_new) {
      $crumbs->addTextCrumb(pht('Create Binding'));
    } else {
      $crumbs->addTextCrumb(pht('Edit Binding'));
    }

    return $this->buildApplicationPage(
      array(
        $crumbs,
        $box,
      ),
      array(
        'title' => $title,
      ));
  }

}
