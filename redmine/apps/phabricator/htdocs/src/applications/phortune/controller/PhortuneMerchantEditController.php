<?php

final class PhortuneMerchantEditController
  extends PhortuneMerchantController {

  private $id;

  public function willProcessRequest(array $data) {
    $this->id = idx($data, 'id');
  }

  public function processRequest() {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    if ($this->id) {
      $merchant = id(new PhortuneMerchantQuery())
        ->setViewer($viewer)
        ->withIDs(array($this->id))
        ->requireCapabilities(
          array(
            PhabricatorPolicyCapability::CAN_VIEW,
            PhabricatorPolicyCapability::CAN_EDIT,
          ))
        ->executeOne();
      if (!$merchant) {
        return new Aphront404Response();
      }
      $is_new = false;
    } else {
      $this->requireApplicationCapability(
        PhortuneMerchantCapability::CAPABILITY);

      $merchant = PhortuneMerchant::initializeNewMerchant($viewer);
      $merchant->attachMemberPHIDs(array($viewer->getPHID()));
      $is_new = true;
    }

    if ($is_new) {
      $title = pht('Create Merchant');
      $button_text = pht('Create Merchant');
      $cancel_uri = $this->getApplicationURI('merchant/');
    } else {
      $title = pht(
        'Edit Merchant %d %s',
        $merchant->getID(),
        $merchant->getName());
      $button_text = pht('Save Changes');
      $cancel_uri = $this->getApplicationURI(
        '/merchant/'.$merchant->getID().'/');
    }

    $e_name = true;
    $v_name = $merchant->getName();
    $v_desc = $merchant->getDescription();
    $v_members = $merchant->getMemberPHIDs();
    $e_members = null;

    $validation_exception = null;
    if ($request->isFormPost()) {
      $v_name = $request->getStr('name');
      $v_desc = $request->getStr('desc');
      $v_view = $request->getStr('viewPolicy');
      $v_edit = $request->getStr('editPolicy');
      $v_members = $request->getArr('memberPHIDs');

      $type_name = PhortuneMerchantTransaction::TYPE_NAME;
      $type_desc = PhortuneMerchantTransaction::TYPE_DESCRIPTION;
      $type_edge = PhabricatorTransactions::TYPE_EDGE;
      $type_view = PhabricatorTransactions::TYPE_VIEW_POLICY;

      $edge_members = PhortuneMerchantHasMemberEdgeType::EDGECONST;

      $xactions = array();

      $xactions[] = id(new PhortuneMerchantTransaction())
        ->setTransactionType($type_name)
        ->setNewValue($v_name);

      $xactions[] = id(new PhortuneMerchantTransaction())
        ->setTransactionType($type_desc)
        ->setNewValue($v_desc);

      $xactions[] = id(new PhortuneMerchantTransaction())
        ->setTransactionType($type_view)
        ->setNewValue($v_view);

      $xactions[] = id(new PhortuneMerchantTransaction())
        ->setTransactionType($type_edge)
        ->setMetadataValue('edge:type', $edge_members)
        ->setNewValue(
          array(
            '=' => array_fuse($v_members),
          ));

      $editor = id(new PhortuneMerchantEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true);

      try {
        $editor->applyTransactions($merchant, $xactions);

        $id = $merchant->getID();
        $merchant_uri = $this->getApplicationURI("merchant/{$id}/");
        return id(new AphrontRedirectResponse())->setURI($merchant_uri);
      } catch (PhabricatorApplicationTransactionValidationException $ex) {
        $validation_exception = $ex;

        $e_name = $ex->getShortMessage($type_name);
        $e_mbmers = $ex->getShortMessage($type_edge);

        $merchant->setViewPolicy($v_view);
      }
    }

    $policies = id(new PhabricatorPolicyQuery())
      ->setViewer($viewer)
      ->setObject($merchant)
      ->execute();

    $member_handles = $this->loadViewerHandles($v_members);

    $form = id(new AphrontFormView())
      ->setUser($viewer)
      ->appendChild(
        id(new AphrontFormTextControl())
          ->setName('name')
          ->setLabel(pht('Name'))
          ->setValue($v_name)
          ->setError($e_name))
      ->appendChild(
        id(new PhabricatorRemarkupControl())
          ->setUser($viewer)
          ->setName('desc')
          ->setLabel(pht('Description'))
          ->setValue($v_desc))
      ->appendChild(
        id(new AphrontFormTokenizerControl())
          ->setDatasource(new PhabricatorPeopleDatasource())
          ->setLabel(pht('Members'))
          ->setName('memberPHIDs')
          ->setValue($member_handles)
          ->setError($e_members))
      ->appendChild(
        id(new AphrontFormPolicyControl())
          ->setName('viewPolicy')
          ->setPolicyObject($merchant)
          ->setCapability(PhabricatorPolicyCapability::CAN_VIEW)
          ->setPolicies($policies))
      ->appendChild(
        id(new AphrontFormSubmitControl())
          ->setValue($button_text)
          ->addCancelButton($cancel_uri));

    $crumbs = $this->buildApplicationCrumbs();
    if ($is_new) {
      $crumbs->addTextCrumb(pht('Create Merchant'));
    } else {
      $crumbs->addTextCrumb(
        pht('Merchant %d', $merchant->getID()),
        $this->getApplicationURI('/merchant/'.$merchant->getID().'/'));
      $crumbs->addTextCrumb(pht('Edit'));
    }

    $box = id(new PHUIObjectBoxView())
      ->setValidationException($validation_exception)
      ->setHeaderText($title)
      ->appendChild($form);

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
