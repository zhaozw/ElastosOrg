<?php

define("mysql_ip", "127.0.0.1");
define("mysql_user", "root");
define("mysql_pwd", "elastos123");
define("mysql_db", "bitnami_phabricator_user");

define("rmysql_ip", "127.0.0.1");
define("rmysql_user", "root");
define("rmysql_pwd", "elastos123");
define("rmysql_db", "wordpress");



final class PhabricatorPasswordAuthProvider extends PhabricatorAuthProvider {

  private $adapter;

  public function getProviderName() {
    return pht('Username/Password');
  }

  public function getConfigurationHelp() {
    return pht(
      "(WARNING) Examine the table below for information on how password ".
      "hashes will be stored in the database.\n\n".
      "(NOTE) You can select a minimum password length by setting ".
      "`account.minimum-password-length` in configuration.");
  }

  public function renderConfigurationFooter() {
    $hashers = PhabricatorPasswordHasher::getAllHashers();
    $hashers = msort($hashers, 'getStrength');
    $hashers = array_reverse($hashers);

    $yes = phutil_tag(
      'strong',
      array(
        'style' => 'color: #009900',
      ),
      pht('Yes'));

    $no = phutil_tag(
      'strong',
      array(
        'style' => 'color: #990000',
      ),
      pht('Not Installed'));

    $best_hasher_name = null;
    try {
      $best_hasher = PhabricatorPasswordHasher::getBestHasher();
      $best_hasher_name = $best_hasher->getHashName();
    } catch (PhabricatorPasswordHasherUnavailableException $ex) {
      // There are no suitable hashers. The user might be able to enable some,
      // so we don't want to fatal here. We'll fatal when users try to actually
      // use this stuff if it isn't fixed before then. Until then, we just
      // don't highlight a row. In practice, at least one hasher should always
      // be available.
    }

    $rows = array();
    $rowc = array();
    foreach ($hashers as $hasher) {
      $is_installed = $hasher->canHashPasswords();

      $rows[] = array(
        $hasher->getHumanReadableName(),
        $hasher->getHashName(),
        $hasher->getHumanReadableStrength(),
        ($is_installed ? $yes : $no),
        ($is_installed ? null : $hasher->getInstallInstructions()),
      );
      $rowc[] = ($best_hasher_name == $hasher->getHashName())
        ? 'highlighted'
        : null;
    }

    $table = new AphrontTableView($rows);
    $table->setRowClasses($rowc);
    $table->setHeaders(
      array(
        pht('Algorithm'),
        pht('Name'),
        pht('Strength'),
        pht('Installed'),
        pht('Install Instructions'),
      ));

    $table->setColumnClasses(
      array(
        '',
        '',
        '',
        '',
        'wide',
      ));

    $header = id(new PHUIHeaderView())
      ->setHeader(pht('Password Hash Algorithms'))
      ->setSubheader(
        pht(
          'Stronger algorithms are listed first. The highlighted algorithm '.
          'will be used when storing new hashes. Older hashes will be '.
          'upgraded to the best algorithm over time.'));

    return id(new PHUIObjectBoxView())
      ->setHeader($header)
      ->appendChild($table);
  }

  public function getDescriptionForCreate() {
    return pht(
      'Allow users to login or register using a username and password.');
  }

  public function getAdapter() {
    if (!$this->adapter) {
      $adapter = new PhutilEmptyAuthAdapter();
      $adapter->setAdapterType('password');
      $adapter->setAdapterDomain('self');
      $this->adapter = $adapter;
    }
    return $this->adapter;
  }

  public function getLoginOrder() {
    // Make sure username/password appears first if it is enabled.
    return '100-'.$this->getProviderName();
  }

  public function shouldAllowAccountLink() {
    return false;
  }

  public function shouldAllowAccountUnlink() {
    return false;
  }

  public function isDefaultRegistrationProvider() {
    return true;
  }

  public function buildLoginForm(
    PhabricatorAuthStartController $controller) {
    $request = $controller->getRequest();
    return $this->renderPasswordLoginForm($request);
  }

  public function buildLinkForm(
    PhabricatorAuthLinkController $controller) {
    throw new Exception("Password providers can't be linked.");
  }

  private function renderPasswordLoginForm(
    AphrontRequest $request,
    $require_captcha = false,
    $captcha_valid = false) {

    $viewer = $request->getUser();

    $dialog = id(new AphrontDialogView())
      ->setSubmitURI($this->getLoginURI())
      ->setUser($viewer)
      ->setTitle(pht('Login to Phabricator'))
      ->addSubmitButton(pht('Login'));

    if ($this->shouldAllowRegistration()) {
      $dialog->addCancelButton(
        '/auth/register/',
        pht('Register New Account'));
    }

    $dialog->addFooter(
      phutil_tag(
        'a',
        array(
          'href' => '/login/email/',
        ),
        pht('Forgot your password?')));

    $v_user = nonempty(
      $request->getStr('username'),
      $request->getCookie(PhabricatorCookies::COOKIE_USERNAME));

    $e_user = null;
    $e_pass = null;
    $e_captcha = null;

    $errors = array();
    if ($require_captcha && !$captcha_valid) {
      if (AphrontFormRecaptchaControl::hasCaptchaResponse($request)) {
        $e_captcha = pht('Invalid');
        $errors[] = pht('CAPTCHA was not entered correctly.');
      } else {
        $e_captcha = pht('Required');
        $errors[] = pht('Too many login failures recently. You must '.
                    'submit a CAPTCHA with your login request.');
      }
    } else if ($request->isHTTPPost()) {
      // NOTE: This is intentionally vague so as not to disclose whether a
      // given username or email is registered.
      $e_user = pht('Invalid');
      $e_pass = pht('Invalid');
      $errors[] = pht('Username or password are incorrect.');
    }

    if ($errors) {
      $errors = id(new AphrontErrorView())->setErrors($errors);
    }

    $form = id(new PHUIFormLayoutView())
      ->setFullWidth(true)
      ->appendChild($errors)
      ->appendChild(
        id(new AphrontFormTextControl())
          ->setLabel('Username or Email')
          ->setName('username')
          ->setValue($v_user)
          ->setError($e_user))
      ->appendChild(
        id(new AphrontFormPasswordControl())
          ->setLabel('Password')
          ->setName('password')
          ->setError($e_pass));

    if ($require_captcha) {
        $form->appendChild(
          id(new AphrontFormRecaptchaControl())
            ->setError($e_captcha));
    }

    $dialog->appendChild($form);

    return $dialog;
  }

  public function processLoginRequest(
    PhabricatorAuthLoginController $controller) {

    $request = $controller->getRequest();
    $viewer = $request->getUser();

    $require_captcha = false;
    $captcha_valid = false;
    if (AphrontFormRecaptchaControl::isRecaptchaEnabled()) {
      $failed_attempts = PhabricatorUserLog::loadRecentEventsFromThisIP(
        PhabricatorUserLog::ACTION_LOGIN_FAILURE,
        60 * 15);
      if (count($failed_attempts) > 5) {
        $require_captcha = true;
        $captcha_valid = AphrontFormRecaptchaControl::processCaptcha($request);
      }
    }

    $response = null;
    $account = null;
    $log_user = null;

    if ($request->isFormPost()) {
      if (!$require_captcha || $captcha_valid) {
        $username_or_email = $request->getStr('username');
        if (strlen($username_or_email)) {
          /*
          *if account is not in phabricator , but is in elastos.org ,create user in phabricator .
          */
          $myconn = mysql_connect(rmysql_ip,rmysql_user,rmysql_pwd) or die("Could not connect : " . mysql_error());
          mysql_select_db(rmysql_db, $myconn) or die("Could not select database");
          $strSql = 'select * from wp_users where user_login = "' . $username_or_email . '"';
          $result = mysql_query($strSql,$myconn) or die("Query failed : " . mysql_error());
          $num = mysql_num_rows($result);
          $row=mysql_fetch_row($result);
          mysql_free_result($result);
          mysql_close($myconn);

          $myconn2 = mysql_connect(mysql_ip,mysql_user,mysql_pwd) or die("Could not connect : " . mysql_error());
          mysql_select_db(mysql_db, $myconn2) or die("Could not select database");
          $strSql2 = 'select * from user where userName = "' . $username_or_email . '"';
          $result2 = mysql_query($strSql2,$myconn2) or die("Query failed hahaa: " . mysql_error());
          $num2 = mysql_num_rows($result2);
          mysql_free_result($result2);
          mysql_close($myconn2);
          if ($num > $num2) {
            //call add_user.php to crete account
            require_once( 'add-user.php' );
            addUserForAdmin("$row[1]","$row[4]","$row[1]","sunzhen");
          }

          $user = id(new PhabricatorUser())->loadOneWhere(
            'username = %s',
            $username_or_email);


          if (!$user) {
            $user = PhabricatorUser::loadOneWithEmailAddress(
              $username_or_email);
          }

          if ($user) {
            $envelope = new PhutilOpaqueEnvelope($request->getStr('password'));
            $passwd = $request->getStr('password');
            require_once( 'class-phpass.php' );
            $wp_hasher = new PasswordHash(8, true);

            /*
             * connect to elastos.org user-database
             */
            $myconn = mysql_connect("127.0.0.1","root", "elastos123") or die("Could not connect : " . mysql_error());
            mysql_select_db("wordpress", $myconn) or die("Could not select database");
            $strSql = 'select user_pass from wp_users where user_login = "' . $username_or_email . '"';
            $result = mysql_query($strSql,$myconn) or die("Query failed : " . mysql_error());

            $num = mysql_fetch_row($result);
            $hash = $num[0];
            $pwdcheck = $wp_hasher->CheckPassword($passwd, $hash);
            mysql_free_result($result);
            mysql_close($myconn);

            $yes = false;
            //if user's account exist in phabricator and elastos.org,we use elastos's passowrd
            if ($pwdcheck > 0) {
              $yes = true;
            } else {
              //only when user's account exist in phabricator but not in elastos.org, we use phabricator's password to check
              if ($user->comparePassword($envelope)) {
                $yes = true;
              }
            }
            if ($yes) {
              $account = $this->loadOrCreateAccount($user->getPHID());
              $log_user = $user;

              // If the user's password is stored using a less-than-optimal
              // hash, upgrade them to the strongest available hash.

              $hash_envelope = new PhutilOpaqueEnvelope(
                $user->getPasswordHash());
              if (PhabricatorPasswordHasher::canUpgradeHash($hash_envelope)) {
                $user->setPassword($envelope);

                $unguarded = AphrontWriteGuard::beginScopedUnguardedWrites();
                  $user->save();
                unset($unguarded);
              }
            }
          }
        }
      }
    }

    if (!$account) {
      if ($request->isFormPost()) {
        $log = PhabricatorUserLog::initializeNewLog(
          null,
          $log_user ? $log_user->getPHID() : null,
          PhabricatorUserLog::ACTION_LOGIN_FAILURE);
        $log->save();
      }

      $request->clearCookie(PhabricatorCookies::COOKIE_USERNAME);

      $response = $controller->buildProviderPageResponse(
        $this,
        $this->renderPasswordLoginForm(
          $request,
          $require_captcha,
          $captcha_valid));
    }

    return array($account, $response);
  }

  public function shouldRequireRegistrationPassword() {
    return true;
  }

  public function getDefaultExternalAccount() {
    $adapter = $this->getAdapter();

    return id(new PhabricatorExternalAccount())
      ->setAccountType($adapter->getAdapterType())
      ->setAccountDomain($adapter->getAdapterDomain());
  }

  protected function willSaveAccount(PhabricatorExternalAccount $account) {
    parent::willSaveAccount($account);
    $account->setUserPHID($account->getAccountID());
  }

  public function willRegisterAccount(PhabricatorExternalAccount $account) {
    parent::willRegisterAccount($account);
    $account->setAccountID($account->getUserPHID());
  }

  public static function getPasswordProvider() {
    $providers = self::getAllEnabledProviders();

    foreach ($providers as $provider) {
      if ($provider instanceof PhabricatorPasswordAuthProvider) {
        return $provider;
      }
    }

    return null;
  }

  public function willRenderLinkedAccount(
    PhabricatorUser $viewer,
    PHUIObjectItemView $item,
    PhabricatorExternalAccount $account) {
    return;
  }

  public function shouldAllowAccountRefresh() {
    return false;
  }

  public function shouldAllowEmailTrustConfiguration() {
    return false;
  }
}

