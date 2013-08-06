<?php

/**
 * Redirect classes to hijack the core UserLogin and CreateAccount facilities, because
 * they're so badly written as to be impossible to extend
 */

class SpecialOpenIDCreateAccount extends SpecialRedirectToSpecial {
	function __construct() {
		parent::__construct( 'SpecialOpenIDCreateAccount', 'OpenIDLogin' );
	}
}
class SpecialOpenIDUserLogin extends SpecialRedirectToSpecial {
	function __construct() {
		parent::__construct( 'SpecialOpenIDUserLogin', 'OpenIDLogin', false, array( 'returnto', 'returntoquery' ) );
	}
}

class OpenIDHooks {
	public static function onSpecialPage_initList( &$list ) {
		global $wgOpenIDLoginOnly, $wgOpenIDConsumerAndAlsoProvider, $wgSpecialPageGroups, $wgOpenIDSmallLogoUrl;

		$wgOpenIDSmallLogoUrl = self::getOpenIDSmallLogoUrl();

		if ( $wgOpenIDLoginOnly ) {
			$list['Userlogin'] = 'SpecialOpenIDLogin';

			# as Special:CreateAccount is an alias for Special:UserLogin/signup
			# we show our own OpenID page here, too
			$list['CreateAccount'] = 'SpecialOpenIDLogin';
		}

		# Special pages are added at global scope;
		# remove server-related ones if client-only flag is set
		$addList = array( 'Login', 'Convert', 'Dashboard', 'Identifier' );
		if ( $wgOpenIDConsumerAndAlsoProvider ) {
			$addList[] = 'Server';
			$addList[] = 'XRDS';
		}

		foreach ( $addList as $sp ) {
			$key = 'OpenID' . $sp;
			$list[$key] = 'SpecialOpenID' . $sp;
			$wgSpecialPageGroups[$key] = 'openid';
		}

		return true;
	}

	/**Hook is called whenever an article is being viewed
	 * @param $article Article
	 * @param $outputDone
	 * @param $pcache
	 * @return bool
	 */
	public static function onArticleViewHeader( &$article, &$outputDone, &$pcache ) {
		$nt = $article->getTitle();

		// If the page being viewed is a user page,
		// generate the openid.server META tag and output
		// the X-XRDS-Location.  See the OpenIDXRDS
		// special page for the XRDS output / generation
		// logic.

		/* pre-version-2.00 behaviour: OpenID Server was only supported for existing userpages */

		if ( $nt 
			&& ( $nt->getNamespace() == NS_USER )
			&& ( strpos( $nt->getText(), '/' ) === false ) ) {

			$user = User::newFromName( $nt->getText() );

			if ( $user && ( $user->getID() != 0 ) ) {
				SpecialOpenIDIdentifier::showOpenIDIdentifier( $user, true, false );
			}
		}

		return true;
	}

	/**
	 * @param $personal_urls array
	 * @param $title Title
	 * @return bool
	 */
	public static function onPersonalUrls( &$personal_urls, &$title ) {
		global $wgHideOpenIDLoginLink, $wgUser, $wgOpenIDLoginOnly;

		if ( !$wgHideOpenIDLoginLink && $wgUser->getID() == 0 ) {
			$sk = $wgUser->getSkin();
			$returnto = $title->isSpecial( 'Userlogout' ) ? '' : ( 'returnto=' . $title->getPrefixedURL() );

			$personal_urls['openidlogin'] = array(
				'text' => wfMessage( 'openidlogin' )->text(),
				'href' => $sk->makeSpecialUrl( 'OpenIDLogin', $returnto ),
				'active' => $title->isSpecial( 'OpenIDLogin' )
			);

			if ( $wgOpenIDLoginOnly ) {
				# remove other login links
				foreach ( array( 'createaccount', 'login', 'anonlogin' ) as $k ) {
					if ( array_key_exists( $k, $personal_urls ) ) {
						unset( $personal_urls[$k] );
					}
				}
			}
		}

		return true;
	}

	/**
	 * @param $out OutputPage
	 * @param $sk
	 * @return bool
	 */
	public static function onBeforePageDisplay( $out, &$sk ) {
		global $wgHideOpenIDLoginLink, $wgUser;

		# We need to do this *before* PersonalUrls is called
		if ( !$wgHideOpenIDLoginLink && $wgUser->getID() == 0 ) {
			$out->addHeadItem( 'openidloginstyle', self::loginStyle() );
		}

		return true;
	}

	/**
	 * @param $user User
	 * @return string
	 */
	private static function getAssociatedOpenIDsTable( $user ) {
		global $wgLang;

		$openid_urls_registration = SpecialOpenID::getUserOpenIDInformation( $user );
		$delTitle = SpecialPage::getTitleFor( 'OpenIDConvert', 'Delete' );

		$rows = '';

		foreach ( $openid_urls_registration as $url_reg ) {
		
			if ( !empty( $url_reg->uoi_user_registration ) ) { 
				$registrationTime = wfMessage(
					'openid-urls-registration-date-time',
					$wgLang->timeanddate( $url_reg->uoi_user_registration, true ),
					$wgLang->date( $url_reg->uoi_user_registration, true ),
					$wgLang->time( $url_reg->uoi_user_registration, true )
				)->text();
			} else {
				$registrationTime = '';
			}

			$rows .= Xml::tags( 'tr', array(),
				Xml::tags( 'td',
					array(),
					self::getOpenIDSmallLogoUrlImageTag() .
						"&nbsp;" .
						Xml::element( 'a', array( 'href' => $url_reg->uoi_openid ), $url_reg->uoi_openid )
				) .
				Xml::tags( 'td',
					array(),
					$registrationTime
				) .
				Xml::tags( 'td',
					array(),
					Linker::link( $delTitle, wfMessage( 'openid-urls-delete' )->text(),
						array(),
						array( 'url' => $url_reg->uoi_openid ) 
					) 
				)
			) . "\n";
		}
		$info = Xml::tags( 'table', array( 'class' => 'wikitable' ),
			Xml::tags( 'tr', array(),
				Xml::element( 'th',
					array(), 
					wfMessage( 'openid-urls-url' )->text() ) .
				Xml::element( 'th',
					array(), 
					wfMessage( 'openid-urls-registration' )->text() ) .
				Xml::element( 'th', 
					array(), 
					wfMessage( 'openid-urls-action' )->text() )
				) . "\n" .
			$rows
		);
		$info .= Linker::link(
			SpecialPage::getTitleFor( 'OpenIDConvert' ),
			wfMessage( 'openid-add-url' )->escaped()
		);
		return $info;
	}

	/**
	 * @param $user User
	 * @return string
	 */
	private static function getTrustTable( $user ) {
		$trusted_sites = SpecialOpenIDServer::GetUserTrustArray( $user );
		$rows = '';

		foreach ( $trusted_sites as $key => $value ) {

			$deleteTrustedSiteTitle = SpecialPage::getTitleFor( 'OpenIDServer', 'DeleteTrustedSite' );

			if ( $key !== "" ) {

				$rows .= Xml::tags( 'tr', array(),
					Xml::tags( 'td',
						array(),
						Xml::element( 'a', array( 'href' => $key ), $key )
					) .
					Xml::tags( 'td',
						array(),
						Linker::link( $deleteTrustedSiteTitle,
							wfMessage( 'openid-trusted-sites-delete-link-action-text' )->text(),
							array(),
							array( 'url' => $key )
						)
					)
				) . "\n";

			}

		}

		if ( $rows !== "" ) {

			$rows .= Xml::tags( 'tr', array(),
				Xml::tags( 'td',
					array(),
					"&nbsp;"
				) .
				Xml::tags( 'td',
					array(),
					Linker::link( $deleteTrustedSiteTitle,
						wfMessage( 'openid-trusted-sites-delete-all-link-action-text' )->text(),
						array(),
						array( 'url' => "*" )
					)
				)
			) . "\n";

		}

		return Xml::tags( 'table', array( 'class' => 'wikitable' ),
			Xml::tags( 'tr', array(),
				Xml::element( 'th',
					array(),
					wfMessage( 'openid-trusted-sites-table-header-column-url' )->text() ) .
				Xml::element( 'th',
					array(),
					wfMessage( 'openid-trusted-sites-table-header-column-action' )->text() )
			) . "\n" .
			$rows
		);
	}

	/**
	 * @param $user User
	 * @param $preferences array
	 * @return bool
	 */
	public static function onGetPreferences( $user, &$preferences ) {
		global $wgOpenIDShowUrlOnUserPage, $wgHiddenPrefs,
			$wgAuth, $wgUser, $wgLang, $wgOpenIDConsumerAndAlsoProvider;

		if ( $wgOpenIDConsumerAndAlsoProvider ) {

			switch ( $wgOpenIDShowUrlOnUserPage ) {

			case 'user':
				$preferences['openid-hide-openid'] =
					array(
						'section' => 'openid/openid-hide-openid',
						'type' => 'toggle',
						'label-message' => 'openid-hide-openid-label',
					);
				break;

			case 'always':
				$preferences['openid-hide-openid'] =
					array(
						'section' => 'openid/openid-hide-openid',
						'type' => 'info',
						'label-message' => 'openid-hide-openid-label',
						'default' => wfMessage( 'openid-show-openid-url-on-userpage-always' )->text(),
					);
				break;

			case 'never':
				$preferences['openid-hide-openid'] =
					array(
						'section' => 'openid/openid-hide-openid',
						'type' => 'info',
						'label-message' => 'openid-hide-openid-label',
						'default' => wfMessage( 'openid-show-openid-url-on-userpage-never' )->text(),
					);
				break;

			}

		}

		// setting up user_properties up_property database key names
		// example 'openid-userinfo-update-on-login-nickname'
		// FIXME: this could better be saved as a JSON encoded array in a single key

		$update = array();
		$update[ wfMessage( 'openidnickname' )->text() ] = '-nickname';
		$update[ wfMessage( 'openidemail' )->text() ] = '-email';
		if ( !in_array( 'realname', $wgHiddenPrefs ) ) {
			$update[ wfMessage( 'openidfullname' )->text() ] = '-fullname';
		}
		$update[ wfMessage( 'openidlanguage' )->text() ] = '-language';
		$update[ wfMessage( 'openidtimezone' )->text() ] = '-timezone';

		$preferences['openid-userinfo-update-on-login'] =
			array(
				'section' => 'openid/openid-userinfo-update-on-login',
				'type' => 'multiselect',
				'label-message' => 'openid-userinfo-update-on-login-label',
				'options' => $update,
			);

		$preferences['openid-associated-openids'] =
			array(
				'section' => 'openid/openid-associated-openids',
				'type' => 'info',
				'label-message' => 'openid-associated-openids-label',
				'default' => self::getAssociatedOpenIDsTable( $user ),
				'raw' => true,
			);

		$preferences['openid_trust'] =
			array(
				'type' => 'hidden',
			);

		if ( $wgOpenIDConsumerAndAlsoProvider ) {

			$preferences['openid-your-openid'] =
				array(
					'section' => 'openid/openid-local-identity',
					'type' => 'info',
					'label-message' => 'openid-local-identity',
					'default' => self::getOpenIDSmallLogoUrlImageTag() . "&nbsp;" . SpecialOpenIDServer::getLocalIdentityLink( $user ),
					'raw' => true,
				);

			$preferences['openid-trusted-sites'] =
				array(
					'section' => 'openid/openid-trusted-sites',
					'type' => 'info',
					'label-message' => 'openid-trusted-sites-label',
					'default' => self::getTrustTable( $user ),
					'raw' => true,
				);

		}

		if ( $wgAuth->allowPasswordChange() ) {
			$resetlink = Linker::link(
				SpecialPage::getTitleFor( 'PasswordReset' ),
				wfMessage( 'passwordreset' )->escaped(),
				array(),
				array( 'returnto' => SpecialPage::getTitleFor( 'Preferences' ) )
			);

			if ( empty( $wgUser->mPassword ) && empty( $wgUser->mNewpassword ) ) {
 				$preferences['password'] = array(
					'section' => 'personal/info',
					'type' => 'info',
					'raw' => true,
					'default' => $resetlink,
					'label-message' => 'yourpassword',
				);
			} else {
				$preferences['resetpassword'] = array(
					'section' => 'personal/info',
					'type' => 'info',
					'raw' => true,
					'default' => $resetlink,
					'label-message' => null,
				);
			}

			global $wgCookieExpiration;
			if ( $wgCookieExpiration > 0 ) {
				unset( $preferences['rememberpassword'] );
				$preferences['rememberpassword'] = array(
					'section' => 'personal/info',
					'type' => 'toggle',
					'label' => wfMessage(
						'tog-rememberpassword',
						$wgLang->formatNum( ceil( $wgCookieExpiration / ( 3600 * 24 ) ) )
						)->escaped(),
				);
			}

		}

		return true;
	}

	/**
	 * @param $user User
	 * @return bool
	 */
	public static function onDeleteAccount( &$user ) {
		global $wgOut;

		if ( is_object( $user ) ) {

			$username = $user->getName();
			$userID = $user->getID();

  			$dbw = wfGetDB( DB_MASTER );

			$dbw->delete( 'user_openid', array( 'uoi_user' => $userID ) );
			$wgOut->addHTML( "OpenID " . wfMessage( 'usermerge-userdeleted', $username, $userID )->escaped() . "<br />\n" );

			wfDebug( "OpenID: deleted OpenID user $username ($userID)\n" );

		}

		return true;

	}

	/**
	 * @param $fromUserObj User
	 * @param $toUserObj User
	 * @return bool
	 */
	public static function onMergeAccountFromTo( &$fromUserObj, &$toUserObj ) {
		global $wgOut, $wgOpenIDMergeOnAccountMerge;

		if ( is_object( $fromUserObj ) && is_object( $toUserObj ) ) {
			$fromUsername = $fromUserObj->getName();
			$fromUserID = $fromUserObj->getID();
			$toUsername = $toUserObj->getName();
			$toUserID = $toUserObj->getID();

			if ( $wgOpenIDMergeOnAccountMerge ) {
				$dbw = wfGetDB( DB_MASTER );

				$dbw->update( 'user_openid', array( 'uoi_user' => $toUserID ), array( 'uoi_user' => $fromUserID ) );
				$wgOut->addHTML( "OpenID " . wfMessage( 'usermerge-updating', 'user_openid', $fromUsername, $toUsername )->escaped() . "<br />\n" );

				wfDebug( "OpenID: transferred OpenID(s) of $fromUsername ($fromUserID) => $toUsername ($toUserID)\n" );

			} else {
				$wgOut->addHTML( wfMessage( 'openid-openids-were-not-merged' )->escaped() . "<br />\n" );
				wfDebug( "OpenID: OpenID(s) were not merged for merged users $fromUsername ($fromUserID) => $toUsername ($toUserID)\n" );
			}
		}
		return true;
	}

	/**
	 * @param $updater DatabaseUpdater
	 * @return bool
	 */
	public static function onLoadExtensionSchemaUpdates( $updater = null ) {
		// >= 1.17 support
		$updater->addExtensionTable( 'user_openid',
			dirname( __FILE__ ) . '/patches/openid_table.sql' );

		# when updating an older OpenID version
		# make the index non unique (remove unique index uoi_user, add new index user_openid_user)
		$db = $updater->getDB();
		$info = $db->fieldInfo( 'user_openid', 'uoi_user' );

		if ( $info && !$info->isMultipleKey() ) {
			$updater->addExtensionUpdate( array( 'dropIndex', 'user_openid', 'uoi_user',
				dirname( __FILE__ ) . '/patches/patch-drop_non_multiple_key_index_uoi_user.sql', true ) );
			$updater->addExtensionIndex( 'user_openid', 'user_openid_user',
				dirname( __FILE__ ) . '/patches/patch-add_multiple_key_index_user_openid_user.sql' );
		}

		# uoi_user_registration field was added in OpenID version 0.937
		$updater->addExtensionField( 'user_openid', 'uoi_user_registration',
			dirname( __FILE__ ) . '/patches/patch-add_uoi_user_registration.sql' );

		return true;
	}

	/**
	 * @return string
	 */
	private static function getOpenIDSmallLogoUrl() {
		global $wgOpenIDSmallLogoUrl, $wgExtensionAssetsPath;

		if ( !$wgOpenIDSmallLogoUrl ) {
			return $wgExtensionAssetsPath . '/OpenID/skin/icons/openid-inputicon.png';
		} else {
			return $wgOpenIDSmallLogoUrl;
		}

	}

	/**
	 * @return string
	 */
	public static function getOpenIDSmallLogoUrlImageTag() {
		return Xml::element( 'img',
			array( 'src' => self::getOpenIDSmallLogoUrl(), 'alt' => 'OpenID' ),
			''
		);
	}

	/**
	 * @return string
	 */
	private static function loginStyle() {
		$openIDLogo = self::getOpenIDSmallLogoUrl();
		return <<<EOS
		<style type='text/css'>
		li#pt-openidlogin {
		  background: url($openIDLogo) top left no-repeat;
		  padding-left: 20px;
		  text-transform: none;
		}
		</style>

EOS;
	}
}
