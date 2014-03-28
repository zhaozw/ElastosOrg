<?php
/*
Plugin Name: Blue Admin Bar
Plugin URI: http://www.helenhousandi.com/wordpress/plugins/blue-admin-bar/
Description: A pretty WordPress blue version of the admin bar. Supports BuddyPress when BP_USE_WP_ADMIN_BAR is true. RTL support for 3.3.
Version: 1.3
Author: Helen Hou-Sandi
Author URI: http://www.helenhousandi.com
*/

add_action( 'init', 'blue_admin_bar_style' );
function blue_admin_bar_style() {
	if ( function_exists('is_admin_bar_showing') && is_admin_bar_showing() ) :
		global $wp_version;
		// comparing versions backward to account for 3.3 beta
		// if the admin bar is showing, then it's not below 3.1 anyway
		if ( version_compare( $wp_version, '3.2.1', '<=' ) )
			wp_enqueue_style( 'blue-admin-bar', plugins_url().'/blue-admin-bar/blue-admin-bar-older.css', array( 'admin-bar' ), '1.3' );
		else
			wp_enqueue_style( 'blue-admin-bar', plugins_url().'/blue-admin-bar/blue-admin-bar.css', array( 'admin-bar' ), '1.3' );
	endif;
}
?>