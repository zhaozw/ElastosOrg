<?php

/*
 * elastos.org special tream program
 */


define('EL_WORDPRESS_INTEGRATE_PATH', '/opt/redmine/apps/wordpress/htdocs');
define('EL_FINAL_WORDPRESS_INTEGRATE_PATH', EL_WORDPRESS_INTEGRATE_PATH.((substr(EL_WORDPRESS_INTEGRATE_PATH, -1)=='/') ? '' : '/'));
define('EL_WORDPRESS_LOAD_FILE', EL_FINAL_WORDPRESS_INTEGRATE_PATH.'wp-load.php');
require_once EL_WORDPRESS_LOAD_FILE;

$nonce=$_REQUEST['_wpnonce'];
if (! wp_verify_nonce($nonce, 'ElastosOrg') ) {
	exit;
}

if ($_REQUEST['fun'] == 'usermail') {
	if (!empty($_REQUEST['user_name'])) {
		global $wpdb;

		$user_login = $_REQUEST['user_name'];
		$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->signups WHERE user_login = %s", $user_login ) );
		if ( !$user ) {
			exit;
		}

		// We use a different email function depending on whether they registered with blog
		if ( !empty( $user->domain ) ) {
			wpmu_signup_blog_notification( $user->domain, $user->path, $user->title, $user->user_login, $user->user_email, $user->activation_key, maybe_unserialize( $user->meta ) );
		} else {
			wpmu_signup_user_notification( $user->user_login, $user->user_email, $user->activation_key, maybe_unserialize( $user->meta ) );
		}
	}
}

?>
