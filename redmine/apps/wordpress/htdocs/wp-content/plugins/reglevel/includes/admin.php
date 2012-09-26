<?php
require_once('functions.php');
if( $_POST[ 'legacy-action' ] == 'update' ) {
    // Read their posted value
	$opt_user = $_POST[ $data_field_name ];
    // Save the posted value in the database
	update_option( 'plugin_reglevel_default', $opt_val );
    // Put an options updated message on the screen
	?>
	<div class="updated"><p><strong><?php _e('Options saved.', 'rl_trans_domain' ); ?></strong></p></div>
	<?php
}
if( $_POST['action'] == 'add' ) {
	// Read posted values
	$update_rlpage = $_POST['redirect'];
	$update_rlrole = $_POST['role'];
	// Save the posted value
	reglevel_create_new_redirect($update_rlpage,$update_rlrole);
	// Put an options updated message on the screen
	?>
	<div class="updated"><p><strong><?php _e('Redirect created.', 'rl_trans_domain' ); ?></strong></p></div>
	<?php
}
if( $_POST['action'] == 'update' ) {
	$update_rlregister = $_POST['users_can_register'];
	$update_rldefault = $_POST['default_role'];
	$update_legacy = $_POST['use_legacy'];
	if($update_legacy == '1')
		$update_legacy = "true";
	update_option('users_can_register', $update_rlregister);
	update_option('default_role', $update_rldefault);
	update_option('RegLevel_Use_Legacy', $update_legacy);
	?>
	<div class="updated"><p><strong><?php _e('Options saved.', 'rl_trans_domain' ); ?></strong></p></div>
	<?php
}
if( $_GET['action'] == 'delete' ) {
	reglevel_delete_redirect($_GET['rl_id']);
	?>
	<div class="updated"><p><strong><?php _e('Redirect deleted.', 'rl_trans_domain'); ?></strong></p></div>
	<?php
}
// Display the options screen
echo '<div class="wrap">';
echo '<h2>RegLevel Settings</h2>';
?>
<table class="widefat fixed" cellspacing="0">
<thead>
<tr class="thead">
	<th scope="col" id="redirect" class="manage-column column-redirect" style="">Redirect</th>
	<th scope="col" id="role" class="manage-column column-role" style="">Role</th>
</tr>
</thead>

<tfoot>
<tr class="thead">
	<th scope="col" id="redirect" class="manage-column column-redirect" style="">Redirect</th>
	<th scope="col" id="role" class="manage-column column-role" style="">Role</th>
</tr>
</tfoot>
<tbody id="reglevel" class="list">
<?php
reglevel_redirect_list();
?>
</tbody>
</table>
<h3>Add New Redirect</h3>
<form method="post">
<input type="hidden" name="action" value="add" />
<table class="widefat fixed" cellspacing="0">
<tbody class="list">
<tr>
<td>Desired URL: <?php bloginfo('url'); ?>/?reglevel=<input type="text" name="redirect" value="" /></td>
<td>Appropriate User Role: <select name="role" id="role"><option value=''><?php _e('Available Roles&hellip;') ?></option><?php wp_dropdown_roles(); ?></select></td>
<td class="submit" style="width:10%;"><input type="submit" value="Save" /></td>
</tr>
</tbody>
</table>
</form>
<form method="post">
<input type="hidden" name="action" value="update" />
<p style="font-size:.8em;"><em>To add additional user roles, I recommend installing a <a href="http://sourceforge.net/projects/role-manager">Role Manager</a> plug-in.</em></p>
<hr /><h2>General Options</h2>
<p class="description">The first two options are copied directly from the WordPress General Settings page.  They are reproduced here to eliminate the need to check multiple settings screens.</p>
<table class="form-table">
<tr valign="top">
<th scope="row"><?php _e('Membership') ?></th>
<td> <fieldset><legend class="screen-reader-text"><span><?php _e('Membership') ?></span></legend><label for="users_can_register">
<input name="users_can_register" type="checkbox" id="users_can_register" value="1" <?php checked('1', get_option('users_can_register')); ?> />
<?php _e('Anyone can register') ?></label>
</fieldset></td>
</tr>
<tr valign="top">
<th scope="row"><label for="default_role"><?php _e('New User Default Role') ?></label></th>
<td>
<select name="default_role" id="default_role"><?php wp_dropdown_roles( get_option('default_role') ); ?></select>
</td>
</tr>
</table>
<p class="submit"><input type="submit" value="Save" /></p>
</form>
<div style="clear:both;text-align:center;display:inline;"><p>Did this work for you?  Please donate to show your support.	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
</form></p></div>
<?php
echo '</div>';
?>