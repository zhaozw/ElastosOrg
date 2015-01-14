<?php

final class PhabricatorAuthNeedsMultiFactorController
  extends PhabricatorAuthController {

  public function shouldRequireMultiFactorEnrollment() {
    // Users need access to this controller in order to enroll in multi-factor
    // auth.
    return false;
  }

  public function processRequest() {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    $panel = id(new PhabricatorSettingsPanelMultiFactor())
      ->setUser($viewer)
      ->setViewer($viewer)
      ->setOverrideURI($this->getApplicationURI('/multifactor/'))
      ->processRequest($request);

    if ($panel instanceof AphrontResponse) {
      return $panel;
    }

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Add Multi-Factor Auth'));

    $viewer->updateMultiFactorEnrollment();

    if (!$viewer->getIsEnrolledInMultiFactor()) {
      $help = id(new AphrontErrorView())
        ->setTitle(pht('Add Multi-Factor Authentication To Your Account'))
        ->setSeverity(AphrontErrorView::SEVERITY_WARNING)
        ->setErrors(
          array(
            pht(
              'Before you can use Phabricator, you need to add multi-factor '.
              'authentication to your account.'),
            pht(
              'Multi-factor authentication helps secure your account by '.
              'making it more difficult for attackers to gain access or '.
              'take senstive actions.'),
            pht(
              'To learn more about multi-factor authentication, click the '.
              '%s button below.',
              phutil_tag('strong', array(), pht('Help'))),
            pht(
              'To add an authentication factor, click the %s button below.',
              phutil_tag('strong', array(), pht('Add Authentication Factor'))),
            pht(
              'To continue, add at least one authentication factor to your '.
              'account.'),
          ));
    } else {
      $help = id(new AphrontErrorView())
        ->setTitle(pht('Multi-Factor Authentication Configured'))
        ->setSeverity(AphrontErrorView::SEVERITY_NOTICE)
        ->setErrors(
          array(
            pht(
              'You have successfully configured multi-factor authentication '.
              'for your account.'),
            pht(
              'You can make adjustments from the Settings panel later.'),
            pht(
              'When you are ready, %s.',
              phutil_tag(
                'strong',
                array(),
                phutil_tag(
                  'a',
                  array(
                    'href' => '/',
                  ),
                  pht('continue to Phabricator')))),
          ));
    }

    return $this->buildApplicationPage(
      array(
        $crumbs,
        $help,
        $panel,
      ),
      array(
        'title' => pht('Add Multi-Factor Authentication'),
      ));
  }

}
