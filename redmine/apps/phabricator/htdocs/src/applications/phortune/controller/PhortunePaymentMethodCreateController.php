<?php

final class PhortunePaymentMethodCreateController
  extends PhortuneController {

  private $accountID;

  public function willProcessRequest(array $data) {
    $this->accountID = $data['accountID'];
  }

  public function processRequest() {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    $account = id(new PhortuneAccountQuery())
      ->setViewer($viewer)
      ->withIDs(array($this->accountID))
      ->executeOne();
    if (!$account) {
      return new Aphront404Response();
    }

    $merchant = id(new PhortuneMerchantQuery())
      ->setViewer($viewer)
      ->withIDs(array($request->getInt('merchantID')))
      ->executeOne();
    if (!$merchant) {
      return new Aphront404Response();
    }

    $cart_id = $request->getInt('cartID');
    if ($cart_id) {
      $cancel_uri = $this->getApplicationURI("cart/{$cart_id}/checkout/");
    } else {
      $cancel_uri = $this->getApplicationURI($account->getID().'/');
    }

    $providers = $this->loadCreatePaymentMethodProvidersForMerchant($merchant);
    if (!$providers) {
      throw new Exception(
        'There are no payment providers enabled that can add payment '.
        'methods.');
    }

    $provider_id = $request->getInt('providerID');
    if (empty($providers[$provider_id])) {
      $choices = array();
      foreach ($providers as $provider) {
        $choices[] = $this->renderSelectProvider($provider);
      }

      $content = phutil_tag(
        'div',
        array(
          'class' => 'phortune-payment-method-list',
        ),
        $choices);

      return $this->newDialog()
        ->setRenderDialogAsDiv(true)
        ->setTitle(pht('Add Payment Method'))
        ->appendParagraph(pht('Choose a payment method to add:'))
        ->appendChild($content)
        ->addCancelButton($cancel_uri);
    }

    $provider = $providers[$provider_id];

    $errors = array();
    if ($request->isFormPost() && $request->getBool('isProviderForm')) {
      $method = id(new PhortunePaymentMethod())
        ->setAccountPHID($account->getPHID())
        ->setAuthorPHID($viewer->getPHID())
        ->setMerchantPHID($merchant->getPHID())
        ->setProviderPHID($provider->getProviderConfig()->getPHID())
        ->setStatus(PhortunePaymentMethod::STATUS_ACTIVE);

      if (!$errors) {
        $errors = $this->processClientErrors(
          $provider,
          $request->getStr('errors'));
      }

      if (!$errors) {
        $client_token_raw = $request->getStr('token');
        $client_token = json_decode($client_token_raw, true);
        if (!is_array($client_token)) {
          $errors[] = pht(
            'There was an error decoding token information submitted by the '.
            'client. Expected a JSON-encoded token dictionary, received: %s.',
            nonempty($client_token_raw, pht('nothing')));
        } else {
          if (!$provider->validateCreatePaymentMethodToken($client_token)) {
            $errors[] = pht(
              'There was an error with the payment token submitted by the '.
              'client. Expected a valid dictionary, received: %s.',
              $client_token_raw);
          }
        }
        if (!$errors) {
          $errors = $provider->createPaymentMethodFromRequest(
            $request,
            $method,
            $client_token);
        }
      }

      if (!$errors) {
        $method->save();

        // If we added this method on a cart flow, return to the cart to
        // check out.
        if ($cart_id) {
          $next_uri = $this->getApplicationURI(
            "cart/{$cart_id}/checkout/?paymentMethodID=".$method->getID());
        } else {
          $account_uri = $this->getApplicationURI($account->getID().'/');
          $next_uri = new PhutilURI($account_uri);
          $next_uri->setFragment('payment');
        }

        return id(new AphrontRedirectResponse())->setURI($next_uri);
      } else {
        $dialog = id(new AphrontDialogView())
          ->setUser($viewer)
          ->setTitle(pht('Error Adding Payment Method'))
          ->appendChild(id(new AphrontErrorView())->setErrors($errors))
          ->addCancelButton($request->getRequestURI());

        return id(new AphrontDialogResponse())->setDialog($dialog);
      }
    }

    $form = $provider->renderCreatePaymentMethodForm($request, $errors);

    $form
      ->setUser($viewer)
      ->setAction($request->getRequestURI())
      ->setWorkflow(true)
      ->addHiddenInput('providerID', $provider_id)
      ->addHiddenInput('cartID', $request->getInt('cartID'))
      ->addHiddenInput('isProviderForm', true)
      ->appendChild(
        id(new AphrontFormSubmitControl())
          ->setValue(pht('Add Payment Method'))
          ->addCancelButton($cancel_uri));

    $box = id(new PHUIObjectBoxView())
      ->setHeaderText($provider->getPaymentMethodDescription())
      ->setForm($form);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Add Payment Method'));

    return $this->buildApplicationPage(
      array(
        $crumbs,
        $box,
      ),
      array(
        'title' => $provider->getPaymentMethodDescription(),
      ));
  }

  private function renderSelectProvider(
    PhortunePaymentProvider $provider) {

    $request = $this->getRequest();
    $viewer = $request->getUser();

    $description = $provider->getPaymentMethodDescription();
    $icon_uri = $provider->getPaymentMethodIcon();
    $details = $provider->getPaymentMethodProviderDescription();

    $this->requireResource('phortune-css');

    $icon = id(new PHUIIconView())
      ->setSpriteSheet(PHUIIconView::SPRITE_LOGIN)
      ->setSpriteIcon($provider->getPaymentMethodIcon());

    $button = id(new PHUIButtonView())
      ->setSize(PHUIButtonView::BIG)
      ->setColor(PHUIButtonView::GREY)
      ->setIcon($icon)
      ->setText($description)
      ->setSubtext($details)
      ->setMetadata(array('disableWorkflow' => true));

    $form = id(new AphrontFormView())
      ->setUser($viewer)
      ->setAction($request->getRequestURI())
      ->addHiddenInput('providerID', $provider->getProviderConfig()->getID())
      ->appendChild($button);

    return $form;
  }

  private function processClientErrors(
    PhortunePaymentProvider $provider,
    $client_errors_raw) {

    $errors = array();

    $client_errors = json_decode($client_errors_raw, true);
    if (!is_array($client_errors)) {
      $errors[] = pht(
        'There was an error decoding error information submitted by the '.
        'client. Expected a JSON-encoded list of error codes, received: %s.',
        nonempty($client_errors_raw, pht('nothing')));
    }

    foreach (array_unique($client_errors) as $key => $client_error) {
      $client_errors[$key] = $provider->translateCreatePaymentMethodErrorCode(
        $client_error);
    }

    foreach (array_unique($client_errors) as $client_error) {
      switch ($client_error) {
        case PhortuneErrCode::ERR_CC_INVALID_NUMBER:
          $message = pht(
            'The card number you entered is not a valid card number. Check '.
            'that you entered it correctly.');
          break;
        case PhortuneErrCode::ERR_CC_INVALID_CVC:
          $message = pht(
            'The CVC code you entered is not a valid CVC code. Check that '.
            'you entered it correctly. The CVC code is a 3-digit or 4-digit '.
            'numeric code which usually appears on the back of the card.');
          break;
        case PhortuneErrCode::ERR_CC_INVALID_EXPIRY:
          $message = pht(
            'The card expiration date is not a valid expiration date. Check '.
            'that you entered it correctly. You can not add an expired card '.
            'as a payment method.');
          break;
        default:
          $message = $provider->getCreatePaymentMethodErrorMessage(
            $client_error);
          if (!$message) {
            $message = pht(
              "There was an unexpected error ('%s') processing payment ".
              "information.",
              $client_error);

            phlog($message);
          }
          break;
      }

      $errors[$client_error] = $message;
    }

    return $errors;
  }

}
