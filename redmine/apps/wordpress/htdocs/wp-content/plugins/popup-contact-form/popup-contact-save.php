<?php
/*
*     Popup contact form
*     Copyright (C) 2011 - 2014 www.gopiplus.com
*     http://www.gopiplus.com/work/2012/05/18/popup-contact-form-wordpress-plugin/
*/

session_start();
$PopupContact_name = isset($_POST['PopupContact_name']) ? $_POST['PopupContact_name'] : '';
$PopupContact_email = isset($_POST['PopupContact_email']) ? $_POST['PopupContact_email'] : '';

if($PopupContact_name <> "" && $PopupContact_email <> "")
{
	$PopupContact_abspath = dirname(__FILE__);
	$PopupContact_abspath_1 = str_replace('wp-content/plugins/popup-contact-form', '', $PopupContact_abspath);
	$PopupContact_abspath_1 = str_replace('wp-content\plugins\popup-contact-form', '', $PopupContact_abspath_1);
	require_once($PopupContact_abspath_1 .'wp-config.php');
	
	$PopupContact_message = $_POST['PopupContact_message'];
	$PopupContact_On_MyEmail = get_option('PopupContact_On_MyEmail');
	$PopupContact_On_Subject = get_option('PopupContact_On_Subject');
		
	if($PopupContact_On_MyEmail <> "YOUR-EMAIL-ADDRESS-TO-RECEIVE-MAILS" && $PopupContact_On_MyEmail <> "")
	{
		$sender_email = mysql_real_escape_string(trim($PopupContact_email));
		$sender_name = mysql_real_escape_string(trim($PopupContact_name));
		$subject = $PopupContact_On_Subject;
		$message = $PopupContact_message;				
	
		$message = preg_replace('|&[^a][^m][^p].{0,3};|', '', $message);
		$message = preg_replace('|&amp;|', '&', $message);
		$mailtext = wordwrap(strip_tags($message), 80, "\n");
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= "From: \"$sender_name\" <$sender_email>\n";
		$headers .= "Return-Path: <" . mysql_real_escape_string(trim($PopupContact_email)) . ">\n";
		$headers .= "Reply-To: \"" . mysql_real_escape_string(trim($PopupContact_name)) . "\" <" . mysql_real_escape_string(trim($PopupContact_email)) . ">\n";
		$mailtext = str_replace("\r\n", "<br />", $mailtext);
		@wp_mail($PopupContact_On_MyEmail, $subject, $mailtext, $headers);
	}
	//echo "Message sent successfully.";
	_e('Message sent successfully.', 'popup-contact');
}
else
{
	//echo "Please enter your name and email.";
	_e('Please enter your name and email.', 'popup-contact');
}
?>