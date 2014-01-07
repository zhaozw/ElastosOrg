<?php

/**
* oauthAdmin handles the the basic main functions of the dashboard
*
* @auther Justin Greer
*/
class oauthAdmin {

	public function __consutruct(){}

	public function __destruct(){}

	/**
	* Consumers that are registered in the system
	*
	* @return int Number of Consumers Registered in the database
	*/
	public function ConsumerCount(){
		global $wpdb;
		$count = $wpdb->query("SELECT * FROM oauth2_clients");
		return $wpdb->num_rows;
	}
	
	/**
	* Formatted list of consumers
	*
	* @return string Formatted list of the registered consumers
	*/
	public function listConsumers(){
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM oauth2_clients");
			foreach($results as $single){
				print '<tr>';
   				print 	'<td><input value=' . $single->name . '/></td>'; 
    			print 	'<td>' . $single->client_id . '</td>'; 
    			print 	'<td>' . $single->client_secret . '</td>'; 
    			print 	'<td><input value="' . $single->redirect_uri . '"/></td>';
    			print 	'<td>' . $single->users_id . '</td>';
				print 	'<td><a href="' . admin_url() . 'admin.php?page=wp_oauth2_complete&edite=' . $single->client_id . '" title="Edite this client" onclick="debugger;if(this.href.match(\'&name\')){this.href=this.href.substring(0,this.href.indexOf(\'&name\')-1);};this.href=this.href+\'&name\'+this.parentNode.previousSibling.previousSibling.previousSibling.previousSibling.previousSibling.childNodes[0].value+\'&redirectURL\'+this.parentNode.previousSibling.previousSibling.childNodes[0].value">Edite</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . admin_url() . 'admin.php?page=wp_oauth2_complete&delete=' . $single->client_id . '" title="Delete this client" onclick="return confirm(\'Are you sure you want to delete this client\')">Delete</a></td>';
				print '</tr>'; 
						
			}
	}
	
}

?>
