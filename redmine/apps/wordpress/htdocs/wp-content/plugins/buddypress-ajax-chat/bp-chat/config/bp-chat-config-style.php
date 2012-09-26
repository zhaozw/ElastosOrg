<?php
// declare the output of the file as CSS
header('Content-type: text/css');
$custom_buddypress_ajax_chat_css = "http://elastos.net/wp-content/plugins/buddypress/bp-themes/bp-default/buddypress-ajax-chat/_inc/buddypress-ajax-chat.css";
$custom_buddypress_ajax_chat_css_file = "/var/elastos.net/wordpress/wp-content/plugins/buddypress/bp-themes/bp-default/buddypress-ajax-chat/_inc/buddypress-ajax-chat.css";
if (file_exists($custom_buddypress_ajax_chat_css_file))
    include ("/var/elastos.net/wordpress/wp-content/plugins/buddypress/bp-themes/bp-default/buddypress-ajax-chat/_inc/buddypress-ajax-chat.css");
?>
