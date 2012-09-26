<?php
global $wpdb;
$table_name = $wpdb->prefix . "reglevel";


if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      
      $sql = "CREATE TABLE " . $table_name . " (
	  ID int(11) NOT NULL AUTO_INCREMENT,
	  pagedef varchar(50) NOT NULL,
	  regleveldef varchar(55) NOT NULL,
	  UNIQUE KEY ID (ID)
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      add_option("Reglevel_DB_Version", "1.0");
}
?>