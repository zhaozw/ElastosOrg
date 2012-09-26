<?php
function reglevel_redirect_list() {
		$redirects = get_reglevel_redirects();
		$total_redirects = count($redirects);
		$style = '';
		foreach($redirects as $redirect) {
			$style = ( ' class="alternate"' == $style ) ? '' : ' class="alternate"';
			$redirect_data=get_single_redirect($redirect);
			echo "\n\t";
			echo "<tr id='redirect-" . $redirect_data['ID'] . "'" . $style . ">";
			echo '<td class="column-redirect">' . get_bloginfo('url') . "/?reglevel=<strong>" . $redirect_data['pagedef'] . "</strong><br />";
			echo '<div class="row-actions">';
//			echo '<span class="edit">';
//			echo "<a href=\"?page=reglevel/includes/admin.php&amp;action=edit&amp;rl_id=" . $redirect_data["ID"] . "\">Edit</a> | ";
//			echo '</span>';
			echo '<span class="delete">';
			echo "<a class=\"submitdelete\" onclick=\"if ( confirm('" . esc_js(sprintf( __("You are about to delete this redirect '%s'\n  'Cancel' to stop, 'OK' to delete."), $redirect_data['pagedef'] )) . "') ) { return true;}return false;\" href=\"?page=reglevel/includes/admin.php&amp;action=delete&amp;rl_id=" . $redirect_data["ID"] . "\">Delete</a>";
			echo '</span>';
			echo "</div>";
			echo "</td>";
			echo "<td class='column-role'>" . ucwords($redirect_data['regleveldef']) . "</td>";
			echo "</tr>";
		}
}

function get_reglevel_redirects() {
     global $wpdb;
     $links = $wpdb->get_col( "SELECT ID FROM " . $wpdb->prefix . "reglevel" );
     return $links;
}

function get_single_redirect($rlid) {
	global $wpdb;
	$query = 'SELECT * FROM ' . $wpdb->prefix . 'reglevel WHERE ID=' . $rlid;
	$sredirect = $wpdb->get_row($query, ARRAY_A);
	return $sredirect;
}

function reglevel_create_new_redirect($page,$role) {
	global $wpdb;
	$query = 'INSERT INTO ' .
			$wpdb->prefix . 'reglevel
			( pagedef, regleveldef )
			VALUES (%s, %s)';
	$wpdb->query( $wpdb->prepare( $query,$page,$role));
}

function reglevel_delete_redirect($rlid) {
	global $wpdb;
	$query = 'DELETE FROM ' . $wpdb->prefix . 'reglevel WHERE ID=%s';
	$wpdb->query( $wpdb->prepare( $query, $rlid ) );
}
?>