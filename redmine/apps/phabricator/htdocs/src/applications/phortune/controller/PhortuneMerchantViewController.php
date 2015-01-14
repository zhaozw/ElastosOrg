<?php

final class PhortuneMerchantViewController
  extends PhortuneMerchantController {

  private $id;

  public function willProcessRequest(array $data) {
    $this->id = $data['id'];
  }

  public function processRequest() {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    $merchant = id(new PhortuneMerchantQuery())
      ->setViewer($viewer)
      ->withIDs(array($this->id))
      ->executeOne();
    if (!$merchant) {
      return new Aphront404Response();
    }

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb($merchant->getName());

    $title = pht(
      'Merchant %d %s',
      $merchant->getID(),
      $merchant->getName());

    $header = id(new PHUIHeaderView())
      ->setObjectName(pht('Merchant %d', $merchant->getID()))
      ->setHeader($merchant->getName())
      ->setUser($viewer)
      ->setPolicyObject($merchant);

    $providers = id(new PhortunePaymentProviderConfigQuery())
      ->setViewer($viewer)
      ->withMerchantPHIDs(array($merchant->getPHID()))
      ->execute();

    $properties = $this->buildPropertyListView($merchant, $providers);
    $actions = $this->buildActionListView($merchant);
    $properties->setActionList($actions);
    $crumbs->setActionList($actions);

    $provider_list = $this->buildProviderList(
      $merchant,
      $providers);

    $box = id(new PHUIObjectBoxView())
      ->setHeader($header)
      ->appendChild($properties);

    $xactions = id(new PhortuneMerchantTransactionQuery())
      ->setViewer($viewer)
      ->withObjectPHIDs(array($merchant->getPHID()))
      ->execute();

    $timeline = id(new PhabricatorApplicationTransactionView())
      ->setUser($viewer)
      ->setObjectPHID($merchant->getPHID())
      ->setTransactions($xactions);

    return $this->buildApplicationPage(
      array(
        $crumbs,
        $box,
        $provider_list,
        $timeline,
      ),
      array(
        'title' => $title,
      ));
  }

  private function buildPropertyListView(
    PhortuneMerchant $merchant,
    array $providers) {

    $viewer = $this->getRequest()->getUser();

    $view = id(new PHUIPropertyListView())
      ->setUser($viewer)
      ->setObject($merchant);

    $status_view = new PHUIStatusListView();

    $have_any = false;
    $any_test = false;
    foreach ($providers as $provider_config) {
      $provider = $provider_config->buildProvider();
      if ($provider->isEnabled()) {
        $have_any = true;
      }
      if (!$provider->isAcceptingLivePayments()) {
        $any_test = true;
      }
    }

    if ($have_any) {
      $status_view->addItem(
        id(new PHUIStatusItemView())
          ->setIcon(PHUIStatusItemView::ICON_ACCEPT, 'green')
          ->setTarget(pht('Accepts Payments'))
          ->setNote(pht('This merchant can accept payments.')));

      if ($any_test) {
        $status_view->addItem(
          id(new PHUIStatusItemView())
            ->setIcon(PHUIStatusItemView::ICON_WARNING, 'yellow')
            ->setTarget(pht('Test Mode'))
            ->setNote(pht('This merchant is accepting test payments.')));
      } else {
        $status_view->addItem(
          id(new PHUIStatusItemView())
          ->setIcon(PHUIStatusItemView::ICON_ACCEPT, 'green')
            ->setTarget(pht('Live Mode'))
            ->setNote(pht('This merchant is accepting live payments.')));
      }
    } else if ($providers) {
      $status_view->addItem(
        id(new PHUIStatusItemView())
          ->setIcon(PHUIStatusItemView::ICON_REJECT, 'red')
          ->setTarget(pht('No Enabled Providers'))
          ->setNote(
            pht(
              'All of the payment providers for this merchant are '.
              'disabled.')));
    } else {
      $status_view->addItem(
        id(new PHUIStatusItemView())
          ->setIcon(PHUIStatusItemView::ICON_WARNING, 'yellow')
          ->setTarget(pht('No Providers'))
          ->setNote(
            pht(
              'This merchant does not have any payment providers configured '.
              'yet, so it can not accept payments. Add a provider.')));
    }

    $view->addProperty(pht('Status'), $status_view);

    $this->loadHandles($merchant->getMemberPHIDs());

    $view->addProperty(
      pht('Members'),
      $this->renderHandlesForPHIDs($merchant->getMemberPHIDs()));

    $view->invokeWillRenderEvent();

    $description = $merchant->getDescription();
    if (strlen($description)) {
      $description = PhabricatorMarkupEngine::renderOneObject(
        id(new PhabricatorMarkupOneOff())->setContent($description),
        'default',
        $viewer);

      $view->addSectionHeader(pht('Description'));
      $view->addTextContent($description);
    }

    return $view;
  }

  private function buildActionListView(PhortuneMerchant $merchant) {
    $viewer = $this->getRequest()->getUser();
    $id = $merchant->getID();

    $can_edit = PhabricatorPolicyFilter::hasCapability(
      $viewer,
      $merchant,
      PhabricatorPolicyCapability::CAN_EDIT);

    $view = id(new PhabricatorActionListView())
      ->setUser($viewer)
      ->setObject($merchant);

    $view->addAction(
      id(new PhabricatorActionView())
        ->setName(pht('Edit Merchant'))
        ->setIcon('fa-pencil')
        ->setDisabled(!$can_edit)
        ->setWorkflow(!$can_edit)
        ->setHref($this->getApplicationURI("merchant/edit/{$id}/")));

    $view->addAction(
      id(new PhabricatorActionView())
        ->setName(pht('View Orders'))
        ->setIcon('fa-shopping-cart')
        ->setHref($this->getApplicationURI("merchant/orders/{$id}/"))
        ->setDisabled(!$can_edit)
        ->setWorkflow(!$can_edit));

    return $view;
  }

  private function buildProviderList(
    PhortuneMerchant $merchant,
    array $providers) {

    $viewer = $this->getRequest()->getUser();
    $id = $merchant->getID();

    $can_edit = PhabricatorPolicyFilter::hasCapability(
      $viewer,
      $merchant,
      PhabricatorPolicyCapability::CAN_EDIT);

    $provider_list = id(new PHUIObjectItemListView())
      ->setNoDataString(pht('This merchant has no payment providers.'));

    foreach ($providers as $provider_config) {
      $provider = $provider_config->buildProvider();
      $provider_id = $provider_config->getID();

      $item = id(new PHUIObjectItemView())
        ->setHeader($provider->getName());

      if ($provider->isEnabled()) {
        if ($provider->isAcceptingLivePayments()) {
          $item->setBarColor('green');
        } else {
          $item->setBarColor('yellow');
          $item->addIcon('fa-exclamation-triangle', pht('Test Mode'));
        }

        $item->addAttribute($provider->getConfigureProvidesDescription());
      } else {
        // Don't show disabled providers to users who can't manage the merchant
        // account.
        if (!$can_edit) {
          continue;
        }
        $item->setDisabled(true);
        $item->addAttribute(
          phutil_tag('em', array(), pht('This payment provider is disabled.')));
      }


      if ($can_edit) {
        $edit_uri = $this->getApplicationURI(
          "/provider/edit/{$provider_id}/");
        $disable_uri = $this->getApplicationURI(
          "/provider/disable/{$provider_id}/");

        if ($provider->isEnabled()) {
          $disable_icon = 'fa-times';
          $disable_name = pht('Disable');
        } else {
          $disable_icon = 'fa-check';
          $disable_name = pht('Enable');
        }

        $item->addAction(
          id(new PHUIListItemView())
            ->setIcon($disable_icon)
            ->setHref($disable_uri)
            ->setName($disable_name)
            ->setWorkflow(true));

        $item->addAction(
          id(new PHUIListItemView())
            ->setIcon('fa-pencil')
            ->setHref($edit_uri)
            ->setName(pht('Edit')));
      }

      $provider_list->addItem($item);
    }

    $add_action = id(new PHUIButtonView())
      ->setTag('a')
      ->setHref($this->getApplicationURI('provider/edit/?merchantID='.$id))
      ->setText(pht('Add Payment Provider'))
      ->setDisabled(!$can_edit)
      ->setWorkflow(!$can_edit)
      ->setIcon(id(new PHUIIconView())->setIconFont('fa-plus'));

    $header = id(new PHUIHeaderView())
      ->setHeader(pht('Payment Providers'))
      ->addActionLink($add_action);

    return id(new PHUIObjectBoxView())
      ->setHeader($header)
      ->appendChild($provider_list);
  }



}
