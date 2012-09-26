<?php
/**
 * Update settings
 * @global type $wp_roles
 * @global class $wpdb
 * @global string $admin_blog_meta
 * @param array $option
 * @param array $roles
 * @param array $blog_post
 * @param array $sticky 
 */
function wpam_update_options ($option, $roles, $blog_post, $sticky) {
   global $wp_roles;
   global $wpdb;
   global $admin_blog_meta;
   
   // Roles
   if ( empty($roles) || ! is_array($roles) ) { 
      $roles = array(); 
   }
   $who_can = $roles;
   $who_cannot = array_diff( array_keys($wp_roles->role_names), $roles);
   foreach ($who_can as $role) {
      $wp_roles->add_cap($role, 'use_wp_admin_microblog');
   }
   foreach ($who_cannot as $role) {
      $wp_roles->remove_cap($role, 'use_wp_admin_microblog');
   }
   
   // Roles for message as a blop post
   if ( empty($blog_post) || ! is_array($blog_post) ) { 
      $blog_post = array(); 
   }
   $who_can = $blog_post;
   $who_cannot = array_diff( array_keys($wp_roles->role_names), $blog_post);
   foreach ($who_can as $role) {
      $wp_roles->add_cap($role, 'use_wp_admin_microblog_bp');
   }
   foreach ($who_cannot as $role) {
      $wp_roles->remove_cap($role, 'use_wp_admin_microblog_bp');
   }
   
   // Roles for sticky message options
   if ( empty($sticky) || ! is_array($sticky) ) { 
      $sticky = array(); 
   }
   $who_can = $sticky;
   $who_cannot = array_diff( array_keys($wp_roles->role_names), $sticky);
   foreach ($who_can as $role) {
      $wp_roles->add_cap($role, 'use_wp_admin_microblog_sticky');
   }
   foreach ($who_cannot as $role) {
      $wp_roles->remove_cap($role, 'use_wp_admin_microblog_sticky');
   }
   
   // Update system values
   $update = "UPDATE " . $admin_blog_meta . " SET `value` = '" . $option['name_blog'] . "' WHERE `variable` = 'blog_name'";
   $wpdb->query( $update );
   $update = "UPDATE " . $admin_blog_meta . " SET `value` = '" . $option['name_widget'] . "' WHERE `variable` = 'blog_name_widget'";
   $wpdb->query( $update );
   $update = "UPDATE " . $admin_blog_meta . " SET `value` = '" . $option['admin_tags'] . "' WHERE `variable` = 'number_tags'";
   $wpdb->query( $update );
   $update = "UPDATE " . $admin_blog_meta . " SET `value` = '" . $option['admin_messages'] . "' WHERE `variable` = 'number_messages'";
   $wpdb->query( $update );
   $update = "UPDATE " . $admin_blog_meta . " SET `value` = '" . $option['auto_reply'] . "' WHERE `variable` = 'auto_reply'";
   $wpdb->query( $update );
   $update = "UPDATE " . $admin_blog_meta . " SET `value` = '" . $option['media_upload'] . "' WHERE `variable` = 'media_upload'";
   $wpdb->query( $update );
   $update = "UPDATE " . $admin_blog_meta . " SET `value` = '" . $option['sticky_for_dash'] . "' WHERE `variable` = 'sticky_for_dash'";
   $wpdb->query( $update );
   $update = "UPDATE " . $admin_blog_meta . " SET `value` = '" . $option['auto_notifications'] . "' WHERE `variable` = 'auto_notifications'";
   $wpdb->query( $update );
} 

/**
 * Settings Page
 * @global type $wp_roles 
 */
function wpam_settings () {
     // run the updater
     wpam_update();
     
     if ( isset($_POST['save']) ) {
          $option = array(
            'admin_tags' => wpam_sec_var($_POST['admin_tags'], 'integer'),
            'admin_messages' => wpam_sec_var($_POST['admin_messages'], 'integer'),
            'auto_reply' => wpam_sec_var($_POST['auto_reply']),
            'media_upload' => wpam_sec_var($_POST['media_upload']),
            'name_blog' => wpam_sec_var($_POST['name_blog']),
            'name_widget' => wpam_sec_var($_POST['name_widget']),
            'sticky_for_dash' => wpam_sec_var($_POST['sticky_for_dash']),
            'auto_notifications' => wpam_sec_var($_POST['auto_notifications'])  
          );
          $userrole = $_POST['userrole'];
          $blog_post = $_POST['blog_post'];
          $sticky = $_POST['sticky'];
          wpam_update_options($option, $userrole, $blog_post, $sticky);
          echo '<div class="updated"><p>' . __('Settings are changed. Please note that access changes are visible, until you have reloaded this page a secont time.','wp_admin_blog') . '</p></div>';
     }

     // load system settings
     $admin_messages = 10;
     $auto_reply = false;
     $name_blog = 'Microblog';
     $name_widget = 'Microblog';
     $admin_tags = 50;
     $media_upload = false;
     $sticky_for_dash = false;
     $auto_notifications = '';
     
     $system = wpam_get_options('','system');
     foreach ($system as $system) {
        if ( $system['variable'] == 'number_messages' ) { $admin_messages = $system['value']; }
        if ( $system['variable'] == 'auto_reply' ) { $auto_reply = $system['value']; }
        if ( $system['variable'] == 'blog_name' ) { $name_blog = $system['value']; }
        if ( $system['variable'] == 'blog_name_widget' ) { $name_widget = $system['value']; }
        if ( $system['variable'] == 'number_tags' ) { $admin_tags = $system['value']; }
        if ( $system['variable'] == 'media_upload' ) { $media_upload = $system['value']; }
        if ( $system['variable'] == 'sticky_for_dash' ) { $sticky_for_dash = $system['value']; }
        if ( $system['variable'] == 'auto_notifications' ) { $auto_notifications = $system['value']; }
     }
     ?>
     <div class="wrap">
     <h2><?php _e('WP Admin Microblog Settings','wp_admin_blog'); ?></h2>
     <form name="form1" id="form1" method="post" action="admin.php?page=wp-admin-microblog/settings.php">
     <input name="page" type="hidden" value="wp-admin-blog" />
     <h3><?php _e('General','wp_admin_blog'); ?></h3>
     <table class="form-table">
        <tr>
             <th scope="row"><?php _e('Name of the Microblog','wp_admin_blog'); ?></th>
             <td style="width: 280px;"><input name="name_blog" type="text" value="<?php echo $name_blog; ?>" size="35" /></td>
             <td><em><?php _e('Default: Microblog','wp_admin_blog'); ?></em></td>
        </tr>
        <tr>
             <th scope="row"><?php _e('Name of the dashboard widget','wp_admin_blog'); ?></th>
             <td><input name="name_widget" type="text" value="<?php echo $name_widget; ?>" size="35" /></td>
             <td><em><?php _e('Default: Microblog','wp_admin_blog'); ?></em></td>
        </tr>
        <tr>
             <th scope="row"><?php _e('Number of tags','wp_admin_blog'); ?></th>
             <td><input name="admin_tags" type="text" value="<?php echo $admin_tags; ?>" size="5" /></td>
             <td><em><?php _e('Default: 50','wp_admin_blog'); ?></em></td>
        </tr>
        <tr>
             <th scope="row"><?php _e('Number of messages per page','wp_admin_blog'); ?></th>
             <td><input name="admin_messages" type="text" value="<?php echo $admin_messages; ?>" size="5"/></td>
             <td><em><?php _e('Default: 10','wp_admin_blog'); ?></em></td>
        </tr>
         <tr>
             <th scope="row"><?php _e('Media upload for the dashboard widget','wp_admin_blog'); ?></th>
             <td><select name="media_upload">
                <?php
                if ($media_upload != false) {
                    echo '<option value="true" selected="selected">' . __('yes','wp_admin_blog') . '</option>';
                    echo '<option value="false">' . __('no','wp_admin_blog') . '</option>';
                }
                else {
                    echo '<option value="true">' . __('yes','wp_admin_blog') . '</option>';
                    echo '<option value="false" selected="selected">' . __('no','wp_admin_blog') . '</option>';
                } 
                ?>
             </select></td>
             <td><em><?php _e('Activate this option to use the media upload for the WP Admin Microblog dashboard widget. If you use it, please notify, that the media upload will not work correctly for QuickPress.','wp_admin_blog'); ?></em></td>
         </tr>
     </table>
     <h3><?php _e('Access','wp_admin_blog'); ?></h3>
     <table class="form-table">
         <tr>
              <th scope="row"><?php _e('Access for','wp_admin_blog'); ?></th>
              <td style="width: 280px;">
              <select name="userrole[]" id="userrole" multiple="multiple" style="height:80px;">
                  <?php
                   global $wp_roles;
                   foreach ($wp_roles->role_names as $roledex => $rolename) {
                       $role = $wp_roles->get_role($roledex);
                       $select = $role->has_cap('use_wp_admin_microblog') ? 'selected="selected"' : '';
                       echo '<option value="'.$roledex.'" '.$select.'>'.$rolename.'</option>';
                   }
                   ?>
              </select>
              </td>
              <td><em><?php _e('Select each user role which has access to WP Admin Microblog.','wp_admin_blog'); ?><br /><?php _e('Use &lt;Ctrl&gt; key to select more than one.','wp_admin_blog'); ?></em></td>
         </tr>
         <tr>
              <th scope="row"><?php _e('"Message as a blog post"-function for','wp_admin_blog'); ?></th>
              <td>
              <select name="blog_post[]" id="blog_post" multiple="multiple" style="height:80px;">
                  <?php
                   foreach ($wp_roles->role_names as $roledex => $rolename) {
                       $role = $wp_roles->get_role($roledex);
                       $select = $role->has_cap('use_wp_admin_microblog_bp') ? 'selected="selected"' : '';
                       echo '<option value="'.$roledex.'" '.$select.'>'.$rolename.'</option>';
                   }
                   ?>
              </select>
              </td>
              <td><em><?php _e('Select each user role which can use the "Message as a blog post"-function.','wp_admin_blog'); ?><br /><?php _e('Use &lt;Ctrl&gt; key to select more than one.','wp_admin_blog'); ?></em></td>
         </tr>
     </table>
     <h3><?php _e('Notifications','wp_admin_blog'); ?></h3>
     <table class="form-table">
          <tr>
             <th scope="row"><?php _e('Auto replies','wp_admin_blog'); ?></th>
             <td style="width: 280px;"><select name="auto_reply">
                <?php
                if ($auto_reply == 'true') {
                echo '<option value="true" selected="selected">' . __('yes','wp_admin_blog') . '</option>';
                     echo '<option value="false">' . __('no','wp_admin_blog') . '</option>';
                }
                else {
                     echo '<option value="true">' . __('yes','wp_admin_blog') . '</option>';
                     echo '<option value="false" selected="selected">' . __('no','wp_admin_blog') . '</option>';
                } 
                ?>
             </select></td>
             <td><em><?php _e('Activate this option and the plugin insert in every reply the string for an e-mail notification to the message author.','wp_admin_blog'); ?></em></td>
         </tr>
         <tr>
             <th><?php _e('Auto notifications','wp_admin_blog'); ?></th>
             <td><textarea id="auto_notifications" name="auto_notifications" style="width: 100%;" rows="5"><?php echo $auto_notifications; ?></textarea></td>
             <td><em><?php _e('Insert your email address, if you want to receive notifications for every new message in the microblog. Insert one email address per line.','wp_admin_blog'); ?></em></td></td>
         </tr>
     </table>
     <h3><?php _e('Sticky messages','wp_admin_blog'); ?></h3>
     <table class="form-table">
         <tr>
              <th scope="row"><?php _e('"Sticky messages"-function for','wp_admin_blog'); ?></th>
              <td style="width: 280px;">
                   <select name="sticky[]" id="sticky" multiple="multiple" style="height:80px;">
                        <?php
                        foreach ($wp_roles->role_names as $roledex => $rolename) {
                            $role = $wp_roles->get_role($roledex);
                            $select = $role->has_cap('use_wp_admin_microblog_sticky') ? 'selected="selected"' : '';
                            echo '<option value="'.$roledex.'" '.$select.'>'.$rolename.'</option>';
                        }
                        ?>
                   </select>
              </td>
              <td><em><?php _e('Select each user role which can add sticky messages.', 'wp_admin_blog'); ?><br /><?php _e('Use &lt;Ctrl&gt; key to select more than one.','wp_admin_blog'); ?></em></td>
         </tr> 
         <tr>
              <th scope="row"><?php _e('Sticky messages for the dashboard widget','wp_admin_blog'); ?></th>
              <td>
                   <select name="sticky_for_dash">
                     <?php
                     if ($sticky_for_dash == 'true') {
                         echo '<option value="true" selected="selected">' . __('yes','wp_admin_blog') . '</option>';
                         echo '<option value="false">' . __('no','wp_admin_blog') . '</option>';
                     }
                     else {
                         echo '<option value="true">' . __('yes','wp_admin_blog') . '</option>';
                         echo '<option value="false" selected="selected">' . __('no','wp_admin_blog') . '</option>';
                     } 
                     ?>
                   </select>
              </td>
              <td><em><?php _e('Select `yes` to display sticky messages in the dashboard widget.','wp_admin_blog'); ?></em></td>
         </tr>
     </table>
     <p class="submit">
     <input type="submit" name="save" id="save" class="button-primary" value="<?php _e('Save Changes', 'wp_admin_blog') ?>" />
     </p>
     </form>
     </div>
     <?php
}
?>
