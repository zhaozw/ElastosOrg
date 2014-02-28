<?php
/*
Plugin Name: Popup contact form
Description: Plugin allows user to creat and add the popup contact forms easily on the website. That popup contact form let user to send the emails to site admin.
Author: Gopi.R
Version: 5.1
Plugin URI: http://www.gopiplus.com/work/2012/05/18/popup-contact-form-wordpress-plugin/
Author URI: http://www.gopiplus.com/work/2012/05/18/popup-contact-form-wordpress-plugin/
Donate link: http://www.gopiplus.com/work/2012/05/18/popup-contact-form-wordpress-plugin/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function PopupContact()
{
	$display = "dontshow";
	if(is_home() && get_option('PopupContact_On_Homepage') == 'YES') {	$display = "show";	}
	if(is_single() && get_option('PopupContact_On_Posts') == 'YES') {	$display = "show";	}
	if(is_page() && get_option('PopupContact_On_Pages') == 'YES') {	$display = "show";	}
	if(is_archive() && get_option('PopupContact_On_Archives') == 'YES') {	$display = "show";	}
	if(is_search() && get_option('PopupContact_On_Search') == 'YES') {	$display = "show";	}
	
	if($display == "show")
	{
		?>
<a href='javascript:PopupContact_OpenForm("PopupContact_BoxContainer","PopupContact_BoxContainerBody","PopupContact_BoxContainerFooter");'><?php echo get_option('PopupContact_Caption'); ?></a>
<div style="display: none;" id="PopupContact_BoxContainer">
  <div id="PopupContact_BoxContainerHeader">
    <div id="PopupContact_BoxTitle"><?php echo get_option('PopupContact_title'); ?></div>
    <div id="PopupContact_BoxClose"><a href="javascript:PopupContact_HideForm('PopupContact_BoxContainer','PopupContact_BoxContainerFooter');"><?php _e('Close', 'popup-contact'); ?></a></div>
  </div>
  <div id="PopupContact_BoxContainerBody">
    <form action="#" name="PopupContact_Form" id="PopupContact_Form">
      <div id="PopupContact_BoxAlert"> <span id="PopupContact_alertmessage"></span> </div>
      <div id="PopupContact_BoxLabel"> <?php _e('Your Name', 'popup-contact'); ?> </div>
      <div id="PopupContact_BoxLabel">
        <input name="PopupContact_name" class="PopupContact_TextBox" type="text" id="PopupContact_name" maxlength="120">
      </div>
      <div id="PopupContact_BoxLabel"> <?php _e('Your Email', 'popup-contact'); ?> </div>
      <div id="PopupContact_BoxLabel">
        <input name="PopupContact_email" class="PopupContact_TextBox" type="text" id="PopupContact_email" maxlength="120">
      </div>
      <div id="PopupContact_BoxLabel"> <?php _e('Enter Your Message', 'popup-contact'); ?> </div>
      <div id="PopupContact_BoxLabel">
        <textarea name="PopupContact_message" class="PopupContact_TextArea" rows="3" id="PopupContact_message"></textarea>
      </div>
      <div id="PopupContact_BoxLabel">
        <input type="button" name="button" class="PopupContact_Button" value="Submit" onClick="javascript:PopupContact_Submit(this.parentNode,'<?php echo get_option('siteurl'); ?>/wp-content/plugins/popup-contact-form/');">
      </div>
    </form>
  </div>
</div>
<div style="display: none;" id="PopupContact_BoxContainerFooter"></div>
<?php
	}
}

function PopupContact_install() 
{
	global $wpdb, $wp_version;
	add_option('PopupContact_title', "Contact Us");
	add_option('PopupContact_fromemail', "admin@contactform.com");
	add_option('PopupContact_On_Homepage', "YES");
	add_option('PopupContact_On_Posts', "YES");
	add_option('PopupContact_On_Pages', "YES");
	add_option('PopupContact_On_Archives', "NO");
	add_option('PopupContact_On_Search', "NO");
	add_option('PopupContact_On_SendEmail', "YES");
	add_option('PopupContact_On_MyEmail', "YOUR-EMAIL-ADDRESS-TO-RECEIVE-MAILS");
	add_option('PopupContact_On_Subject', "EMAIL-SUBJECT");
	add_option('PopupContact_On_Captcha', "YES");
	add_option('PopupContact_Caption', "<img src='".get_option('siteurl')."/wp-content/plugins/popup-contact-form/popup-contact-form.jpg' />");
}

function PopupContact_widget($args) 
{
	$display = "dontshow";
	if(is_home() && get_option('PopupContact_On_Homepage') == 'YES') {	$display = "show";	}
	if(is_single() && get_option('PopupContact_On_Posts') == 'YES') {	$display = "show";	}
	if(is_page() && get_option('PopupContact_On_Pages') == 'YES') {	$display = "show";	}
	if(is_archive() && get_option('PopupContact_On_Archives') == 'YES') {	$display = "show";	}
	if(is_search() && get_option('PopupContact_On_Search') == 'YES') {	$display = "show";	}
	
	$title = get_option('PopupContact_title');
	if($display == "show")
	{
		extract($args);
	    echo $before_widget;
		PopupContact();
		echo $after_widget;
	}
}
	
function PopupContact_control() 
{
	echo '<p>';
	_e('Check official website for more information', 'popup-contact');
	?> <a target="_blank" href="http://www.gopiplus.com/work/2012/05/18/popup-contact-form-wordpress-plugin/"><?php _e('click here', 'popup-contact'); ?></a></p><?php
}

function PopupContact_widget_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget( __('Popup contact form', 'popup-contact'), __('Popup contact form', 'popup-contact'), 'PopupContact_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control( __('Popup contact form', 'popup-contact'), array(__('Popup contact form', 'popup-contact'), 'widgets'), 'PopupContact_control');
	} 
}

function PopupContact_deactivation() 
{
	// No action required.
}

function PopupContact_admin()
{
	global $wpdb;
	include('content-setting.php');
}

function PopupContact_add_to_menu() 
{
	add_options_page( __('Popup contact form', 'popup-contact'), __('Popup contact form', 'popup-contact'), 'manage_options', __FILE__, 'PopupContact_admin' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'PopupContact_add_to_menu');
}

function PopupContact_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_style( 'popup-contact-form', get_option('siteurl').'/wp-content/plugins/popup-contact-form/popup-contact-form.css');
		wp_enqueue_script( 'popup-contact-form', get_option('siteurl').'/wp-content/plugins/popup-contact-form/popup-contact-form.js');
		wp_enqueue_script( 'popup-contact-popup', get_option('siteurl').'/wp-content/plugins/popup-contact-form/popup-contact-popup.js');
	}
}   

function PopupContact_shortcode( $atts ) 
{
	//[popup-contact-form id="1" title="Contact Us"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	
	$id = $atts['id'];
	$title = $atts['title'];
	
	$PopupContact_Caption = get_option('PopupContact_Caption');
	$PopupContact_title = $title;
	$siteurl = "'".get_option('siteurl') . "/wp-content/plugins/popup-contact-form/'";
	$close = "javascript:PopupContact_HideForm('PopupContact_BoxContainer','PopupContact_BoxContainerFooter');";
	$open = 'javascript:PopupContact_OpenForm("PopupContact_BoxContainer","PopupContact_BoxContainerBody","PopupContact_BoxContainerFooter");';
	
	$html = "<a href='".$open."'>".$PopupContact_Caption."</a>";
	$html .= '<div style="display: none;" id="PopupContact_BoxContainer">';
	  $html .= '<div id="PopupContact_BoxContainerHeader">';
		$html .= '<div id="PopupContact_BoxTitle">'.$PopupContact_title.'</div>';
		$html .= '<div id="PopupContact_BoxClose"><a href="'.$close.'">'.__('Close', 'popup-contact').'</a></div>';
	  $html .= '</div>';
	  $html .= '<div id="PopupContact_BoxContainerBody">';
		$html .= '<form action="#" name="PopupContact_Form" id="PopupContact_Form">';
		  $html .= '<div id="PopupContact_BoxAlert"> <span id="PopupContact_alertmessage"></span> </div>';
		  $html .= '<div id="PopupContact_BoxLabel_Page"> '.__('Your Name', 'popup-contact').' </div>';
		  $html .= '<div id="PopupContact_BoxLabel_Page">';
			$html .= '<input name="PopupContact_name" class="PopupContact_TextBox" type="text" id="PopupContact_name" maxlength="120">';
		  $html .= '</div>';
		  $html .= '<div id="PopupContact_BoxLabel_Page"> '.__('Your Email', 'popup-contact').' </div>';
		  $html .= '<div id="PopupContact_BoxLabel_Page">';
			$html .= '<input name="PopupContact_email" class="PopupContact_TextBox" type="text" id="PopupContact_email" maxlength="120">';
		  $html .= '</div>';
		  $html .= '<div id="PopupContact_BoxLabel_Page"> '.__('Enter Your Message', 'popup-contact').' </div>';
		  $html .= '<div id="PopupContact_BoxLabel_Page">';
			$html .= '<textarea name="PopupContact_message" class="PopupContact_TextArea" rows="3" id="PopupContact_message"></textarea>';
		  $html .= '</div>';
		  $html .= '<div id="PopupContact_BoxLabel_Page">';
			$html .= '<input type="button" name="button" class="PopupContact_Button" value="Submit" onClick="javascript:PopupContact_Submit(this.parentNode,'.$siteurl.');">';
		  $html .= '</div>';
		$html .= '</form>';
	  $html .= '</div>';
	$html .= '</div>';
	$html .= '<div style="display: none;" id="PopupContact_BoxContainerFooter"></div>';
	
	return $html;
}

function PopupContact_textdomain() 
{
	  load_plugin_textdomain( 'popup-contact', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'PopupContact_textdomain');
add_shortcode( 'popup-contact-form', 'PopupContact_shortcode' );
add_action('wp_enqueue_scripts', 'PopupContact_add_javascript_files');
add_action("plugins_loaded", "PopupContact_widget_init");
register_activation_hook(__FILE__, 'PopupContact_install');
register_deactivation_hook(__FILE__, 'PopupContact_deactivation');
add_action('init', 'PopupContact_widget_init');
?>