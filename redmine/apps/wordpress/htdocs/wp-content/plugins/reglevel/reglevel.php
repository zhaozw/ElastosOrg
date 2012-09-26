<?php
/*
Plugin Name: RegLevel
Plugin URI: http://www.jumping-duck.com/wordpress/
Description: Allows you to specify different registration screens for each user level.  Please read about changes between version 0.3 and version 1.0 before upgrading!  The way you specify user levels and registration screens has completely changed!  To set up RegLevel redirects, visit <a href="users.php?page=reglevel/includes/admin.php">Users &raquo; RegLevel</a>.
Version: 1.2.1
Author: Eric Mann
Author URI: http://www.eamann.com
License: GPLv2+
*/

/*  Copyright 2008-10  ERIC MANN  (email : eric@eamann.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or 
	(at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Define Variables */
global $reporter;
if( ! defined( 'RL_VER' ))
	define( 'RL_VER', '1.2.0' );
if( ! defined( 'RL_BASE' ))
	define( 'RL_BASE' , dirname(__FILE__) );
if( ! defined( 'RL_DIRECTORY' ))
	define( 'RL_DIRECTORY' , get_option('siteurl') . '/wp-content/plugins/reglevel' );
if( ! defined( 'RL_INC' ))
	define( 'RL_INC' , RL_DIRECTORY . '/includes' );
if( ! defined( 'RL_BASE_INC' ))
	define( 'RL_BASE_INC', RL_BASE . '/includes' );

/* Check to see if this is a new installation or an upgrade */
$current_ver = get_option('RegLevel_Version');

if( $current_ver && version_compare($current_ver, RL_VER, '<')) {
	// Clean up upgrades
	delete_option('plugin_reglevel_user');
	delete_option('plugin_reglevel_page');
	delete_option('plugin_reglevel_default');
	delete_option('plugin_reglevel_set');
	delete_option('plugin_reglevel_version');
}

if( version_compare($current_ver, '1.0', '<') || !$current_ver )
	include(RL_BASE_INC . '/install.php');

update_option('RegLevel_Version', '1.2.0');

/*
 * Sets admin warnings regarding required PHP and WordPress versions.
 */
function _rl_wp_warning() {
	$data = get_plugin_data(__FILE__);
	
	echo '<div class="error"><p><strong>' . __('Warning:') . '</strong> '
		. sprintf(__('The active plugin %s is not compatible with your WordPress version.') .'</p><p>',
			'&laquo;' . $data['Name'] . ' ' . $data['Version'] . '&raquo;')
		. sprintf(__('%s is required for this plugin.'), 'WordPress 2.8 ');
	echo '</p></div>';
}

// START PROCEDURE

// Check required WordPress version.
if ( version_compare(get_bloginfo('version'), '2.8', '<')) {
	add_action('admin_notices', '_rl_wp_warning');
} else {
	include_once ( RL_BASE_INC . '/core.php' );
}

?>