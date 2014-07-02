<?php
/*
Plugin Name: BP Admin Actions
Plugin URI: http://ovirium.com/plugins/bp-admin-actions/
Description: Let admin do some more stuff with users - adding them to any group manually from members list
Author: slaFFik
Version: 1.0
Author URI: http://cosydale.com/
Domain Path: /langs/
Text Domain: bp_admin_actions
*/
define('BPAA_URL', WP_PLUGIN_URL . '/bp-admin-actions');
define('BPAA_DIR', WP_PLUGIN_DIR . '/bp-admin-actions');

function bpaa_init() {
    add_action('bp_loaded', 'bpaa_load_textdomain', 3);
    add_action('wp_head','bpaa_add_user_to_group_inc_css');
    add_action('wp_print_scripts', 'bpaa_add_user_to_group_inc_js');
    add_action('wp_footer', 'bpaa_add_user_to_group_popup');
    add_action('bp_directory_members_actions','bpaa_add_user_to_group_button');
}
add_action( 'bp_include', 'bpaa_init' );

function bpaa_load_textdomain() {
    $locale = apply_filters( 'buddypress_locale', get_locale() );
    $mofile = BPAA_DIR . "/langs/$locale.mo";

    if ( file_exists( $mofile ) )
        load_textdomain( 'bp_admin_actions', $mofile );
}

function bpaa_add_user_to_group_inc_css(){
    wp_enqueue_style('bp-admin-actions-css', BPAA_URL . '/_inc/style.css');
    wp_print_styles();
}

function bpaa_add_user_to_group_inc_js(){ ?>
<script type='text/javascript'>
/* <![CDATA[ */
var bpaa_strings = {
    user_added: "<?php _e('User added','bp_admin_actions') ?>",
    error_again: "<?php _e('Error: try again','bp_admin_actions') ?>",
    please_select: "<?php _e('Please, select a group for this user','bp_admin_actions') ?>"
};
/* ]]> */
</script>
<?php
    wp_enqueue_script('bp-admin-actions-js', BPAA_URL . '/_inc/engine.js');
}

function bpaa_add_user_to_group_popup(){
    if(is_site_admin())
        echo '<div id="popupGroups"></div><div id="backgroundPopup"></div>';
}

function bpaa_add_user_to_group_ajax(){
    echo '
    <a id="popupGroupsClose" title="'.__('Close this window','bp_admin_actions').'">x</a>
    <h1>'.sprintf(__('Add %s to a group','bp_admin_actions'),bp_core_get_userlink($_GET['user'])).' </h1>
    <p id="popupArea">'.__('Select a group you want this user to become a member of.','bp_admin_actions').'</p>
    <p id="popupArea">';
    $args = array(
        'page' => 1,
        'type' => 'active',
        'per_page' => 100,
        'max' => false,
        'user_id' => false, // Pass a user ID to limit to groups this user has joined
        'populate_extras' => false // Get extra meta - is_member, is_banned
    );
    if ( bp_has_groups( $args ) ) {
        echo '<select id="selected_group">
                <option value="0">'.__('select a group','bp_admin_actions').'</option>';
        while ( bp_groups() ) : bp_the_group();
            if ( !groups_is_user_member($_GET['user'], bp_get_group_id()) )
                echo '<option value="'.bp_get_group_id().'">'.bp_get_group_name().'</option>';
        endwhile;
        echo'</select><a href="#" style="float:right" rel="'.$_GET['user'].'" class="button" id="add_to_selected">'.__('Add to this group','bp_admin_actions').'</a>';
        
    }else{
        _e('No groups to display','bp_admin_actions');
    }
    echo '</p>
        <p id="popupArea" class="popupReport"></p>';
    die();
}
add_action('wp_ajax_bpaa_add_user_to_group_ajax','bpaa_add_user_to_group_ajax');

function bpaa_add_user_to_group_silent_ajax(){
    if (!groups_join_group($_GET['group'], $_GET['user'])) {
        echo '0';
    }else{
        echo '1';
    }
    die();
}
add_action('wp_ajax_bpaa_add_user_to_group_silent_ajax','bpaa_add_user_to_group_silent_ajax');

function bpaa_add_user_to_group_button(){
    if(is_site_admin())
        echo '<div style="margin: 5px 0;" class="generic-button">
            <a class="add_to_group" rel="'.bp_get_member_user_id().'" href="#">'.__('Add To Group','bp_admin_actions').'</a>
            </div>';
}

?>
