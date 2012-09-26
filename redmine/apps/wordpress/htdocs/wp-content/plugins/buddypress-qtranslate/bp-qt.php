<?php
	
	/*
	 * Plugin Name: Buddypress - qTranslate
	 * Author: Dominik Matus
	 * Plugin URI: http://dominikmatus.cz/buddypress-qtranslate
	 * Author URI: http://dominikmatus.cz
	 * Description: Plugin for optimize qTranslate with Buddypress and support of BP Admin bar
	 * Version: 1.2
	*/
/* this plugin load localization from Wordpress and qTranslate*/


load_plugin_textdomain('qtranslate','/wp-content/plugins/qtranslate/lang/');


function qtrans_generateLanguageSelectCodeCustom($style='', $id='qtranslate') {
$url = get_option('home');
	global $q_config;
	if($style=='') $style='text';
	if($id!='') $id .= '-chooser';
	if(is_bool($style)&&$style) $style='image';
	if(is_404()) $url = get_option('home'); else $url = '';
	switch($style) {
		case 'image':
		case 'text':
		case 'dropdown':
			echo '<ul class="qtrans_language_chooser" id="'.$id.'">';
			foreach(qtrans_getSortedLanguages() as $language) {
				echo '<li';
				if($language == $q_config['language'])
					echo ' class="active"</li>';
				echo '>';			
			}
			break;
		case 'both':
			echo '<li><a>'.__('Languages').'</a><ul class="qtrans_language_chooser" id="'.$id.'">';
			foreach(qtrans_getSortedLanguages() as $language) {
				echo '<li';
				if($language == $q_config['language'])
					echo ' class="active" id="langli"';					
				echo '>';
				echo '<a href="'.qtrans_convertURL($url, $language).'"><img style="padding-left:3px;padding-right:5px;" src="'.WP_CONTENT_URL.'/'.$q_config['flag_location'].$q_config['flag'][$language].'"></img>'.$q_config['language_name'][$language].'</a></li>';
			}
			echo "</ul></li>";
			break;
	}
}
  
  function adminbar_qtranslate_button() {
    // adds a language button to the BuddyPress admin bar if a user is logged in.
    //if (is_user_logged_in()) {
         
        echo qtrans_generateLanguageSelectCodeCustom('both');
        
    //}
   }


// function of Dashboard button 
 
 function custom_adminbar_dashboard_button() {
    // adds a "Dashboard" link to the BuddyPress admin bar if a user is logged in.
  
    if (is_user_logged_in()) {
        echo '<li class="align-right" id="bp-adminbar-dashboard"><a href="/wp-admin/">'.__('Dashboard').'</a></li>';
    }
   }
   
// function of Dashboard button END

 
 function my_bp_adminbar(){
add_action('bp_adminbar_menus', 'adminbar_qtranslate_button', 1);
 
//this code remove Logo in admin bar  
remove_action( 'bp_adminbar_logo', 'bp_adminbar_logo' );
//this code remove login in admin bar  
//remove_action( 'bp_adminbar_menus', 'bp_adminbar_login_menu', 2 );
//this code remove random button in admin bar  
remove_action( 'bp_adminbar_menus', 'bp_adminbar_random_menu', 100 );
//this code add dashboard button to admin bar  
add_action('bp_adminbar_menus', 'custom_adminbar_dashboard_button', 1000);
}

add_action('wp_footer','my_bp_adminbar',1);
remove_filter('clean_url', 'qtrans_convertURL',1);

?>
