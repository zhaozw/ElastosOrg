<?php

function jc_admin_load() {

    if (array_key_exists('page', $_REQUEST) && 'jc_options' == $_REQUEST['page']) {
        add_action('admin_head', 'jc_admin_options_head');
        wp_enqueue_script('jc-scripts', get_template_directory_uri() . '/include/admin.js', array('jquery'), '1.0.0', true);
    } else {
        add_action('admin_head', 'jc_admin_head');
    }

}



function jc_admin_resources(){

}

function jc_admin_options_head() {
    ?>
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() ?>/include/admin.css" />
    <script type="text/javascript" language="javascript">
    jQuery(document).ready(function(){
        jQuery('#jc_tab_menu>li:first').addClass('active');
        jQuery('#jc_tab').tabify();
    });
    </script>
    <?php
}
function jc_admin_head() {
    ?>
    <script type="text/javascript" language="javascript">
    jQuery(document).ready(function(){
        var $shortcodeTrigger = jQuery('#add_jc_shortcodes');
        if ($shortcodeTrigger.length != 0)
            tb_position();
    });
    </script>
    <?php
}


function jc_theme_menu() {
    add_theme_page('JC Theme Options', 'JC Theme', 'edit_theme_options', 'jc_options', 'jc_options_page');
}

function jc_options_page() {

    global $jc_params;
    $jc_message = "";

    $tab = (isset($_REQUEST['tab'])) ? intval($_REQUEST['tab']) : 1;
    $errors = array();

    // we have submited the page -> save
    if ('jc_options' == $_REQUEST['page']) {
        if (isset($_REQUEST['action']) && ('update' == $_REQUEST['action'])) {
            $errors = jc_options_page_save($jc_params);
            if (count($errors) == 0){
                wp_redirect("themes.php?page=jc_options&message=1&tab=" . $tab);
            } else {
                $jc_message = __("Options saved with errors", 'jc-one-lite');
            }
        }
        else {
            if (isset($_REQUEST['message']))
                $jc_message = __("Options saved", 'jc-one-lite');
        }
    }


    echo '<div class="wrap">';
    echo '<div class="space">&nbsp;</div>';

    echo '<div id="donation">';
    echo '
    <div class="buttons">
        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8GGWRXZAAKPHW" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate" title="Donate with Paypal" border="0" /></a>
        <span class="twitter"><a href="https://twitter.com/netcod_es" class="twitter-follow-button" target="_blank">follow us @netcod_es</a></span>
        <span class="website"><a href="http://www.netcod.es/" target="_blank">support on Netcodes</a></span>
    </div>';
    echo '<h3>'.__('Why not make a donation :)', 'jc-one-lite').'</h3>';
    echo '<p>'.__('You know, making a theme is a hard work and is time (and beer) consuming. So if you like this theme, any kind of contribution would be <strong>highly appreciated</strong>.', 'jc-one-lite').'</p>';
    echo '<p>'.__('You can also just make <strong><a href="https://twitter.com/intent/tweet?source=webclient&text=JC%20One%20Lite%20%3A%20A%20nice%20%23wordpress%20theme%20by%20%40netcod_es%20%3A%20http%3A%2F%2Fwww.netcod.es%2F" target="_blank">a simple tweet</a></strong>.', 'jc-one-lite').'</p>';
    echo '<p><strong>'.__('Thank you.', 'jc-one-lite').'</strong></p>';
    echo '</div>';


    echo '<div class="space">&nbsp;</div>';
    if ($jc_message != ""){
        echo '<div class="updated fade below-h2" style="background-color: rgb(255, 251, 204);"><p>'.$jc_message.'</p>';
        if (count($errors) == 0){
            echo '<ul>';
            foreach ($errors as $key => $error) {
                echo '<li>' . $error . '</li>';
            }
        echo '</ul>';
        }
        echo '</div>';
    }

    //echo '<form action="'.$_SERVER['REQUEST_URI'].'" method="post" onsubmit="return false;" enctype="multipart/form-data">';
    echo '<form action="themes.php?page=jc_options&noheader=true" method="post" enctype="multipart/form-data">';
    echo wp_nonce_field('update-options','nonce');

    $fields = '';
    $start_table = false;
    $debug = '';
    $embedded = '';

    // pour le menu
    $tab_menu = "\n".'<div id="jc_tab">';
    $tab_menu .= "\n".'<div id="jc_tab_header"><h2>'.__('Theme Options', 'jc-one-lite').'</h2></div>';
    $tab_menu .= "\n".'<ul id="jc_tab_menu">';
    $tab_index = 1;

    foreach ($jc_params as $key => $option) {
        if ($option['type'] == "set"){
            $active_tab = ($tab_index == $tab) ? ' class="active"' : '';
            $tab_menu .= '<li '.$active_tab.'><a href="#jc_tab_'.$option['id'].'">'.$option['name'].'</a></li>';

            $tab_index++;
        }
    }
    $tab_menu .= "</ul><!-- #jc_tab -->\n<div id='jc_tab_content'>";
    echo $tab_menu;
    foreach ($jc_params as $key => $option){
        $out = "";
        $current = "";
        if (array_key_exists('id', $option)){
            $id = $option['id'];
            $current = jc_get_option($id);
        }else{
             $id = "ui";
        }
        $debug .= $id . "=" . $current . "<br />";
        switch ( $option['type'] ) {
            case 'set':
                // $out = "<h3>".$option['name']."</h3>";
                $out = '';
                if ($option['help'] != ''){
                    $out .= "<p>".$option['help']."</p>";
                }
                break;
            case 'text':
                $out = "<input type='text' id='".esc_attr($id)."' name='".esc_attr($id)."' value='".esc_attr($current)."' {{ style }} />";
                break;
            case 'textarea':
                $out = "<textarea id='".esc_attr($id)."' name='".esc_attr($id)."' {{ style }} >".esc_textarea($current)."</textarea>";
                break;
            case 'select':
                $out = "<select id='".esc_attr($id)."' name='".esc_attr($id)."' {{ style }} >";
                foreach ($option['choices'] as $key => $choice) {
                    $out .= "<option value='".esc_attr($choice["value"])."' ".selected($choice["value"], $current, false)." >".$choice["name"]."</option>";
                }
                $out .= "</select>";
                break;
            case 'radio':
                $out = "";
                foreach ($option['choices'] as $key => $choice) {
                    $out .= "<input name='".esc_attr($id)."' type='radio' value='".esc_attr($choice["value"])."' ".checked($choice["value"], $current, false)." > ".$choice["name"]."<br />" ;
                }
                break;
            case 'checkbox':
                $out = "";
                foreach ($option['choices'] as $key => $choice) {
                    if ($out != "") $out .= "<br />";
                    $out .= "<input name='".esc_attr($id)."' type='checkbox' value='".esc_attr($choice["value"])."' ".checked($choice["value"], $current, false)." > ".$choice["name"];
                }
                break;
            case 'upload':
                $out .= jc_get_upload_field($option);
                break;
        }

        // on remplace les params
        if (array_key_exists('options', $option)){
            foreach ($option['options'] as $key => $param) {
                $out = str_replace( '{{ '.$key.' }}', $param, $out);
            }
        }
        // on nettoie les params non utilisés
        $out = str_replace( '/\{\{ [\w\-_]+ \}\}/', "", $out);


        if ($option['type']=="set"){
            if ($start_table) {
                if ($embedded == '') echo '</table>';
                echo '</div>'."\n";
            }
            echo '<div id="jc_tab_'.$option['id'].'" class="jc_tab_content">';
            echo $out;
            $embedded = (isset($option['embedded']) && $option['embedded'] != '')
                    ? $option['embedded'] : '';
            if ($embedded == '')
                echo '<table class="form-table">';
            else{
                require_once($embedded.'.php');
            }
            $start_table = true;
        } else {
            if ($fields != '') $fields .= ',';
            $fields .= $id;
            // $out = $option['type'];

            echo '<tr valign="top">';
            echo '<th scope="row">'.$option['name'].'</th>';
            echo '<td>'.$out;
            if (isset($option['help'])){
                echo '<br/><span class="description">'.$option['help'].'</span>';
            }
            echo '</td></tr>';
        }
    }

    if ($start_table){
        echo "</table></div>\n";
    }
    echo "</div><!-- #jc_tab_content -->\n";
    echo '<div id="jc_tab_footer"><input type="hidden" name="action" value="update" />';
    echo '<input type="hidden" name="page_options" value="'.$fields.'" />';
    // echo '<div>' . $debug . '<div/>';
    echo '<input type="submit" class="button-primary" value="'.__('Save Changes', 'jc-one-lite').'" />';
    echo "</div><!-- #jc_tab_footer -->\n</div><!-- #jc_tab -->\n</form>";
    echo '</div>';
}


function jc_get_upload_field($option){


    $id = $option['id'];

    $temp = __("upload file : ", 'jc-one-lite');
    $temp .= '<input type="file" name="file_'.$id.'" /><br />';
    $temp .= __("or the url : ", 'jc-one-lite');

    $file = jc_get_option($id);
    if (empty($file)) {
        $temp .= '<input class="upload-input-text" name="'.esc_attr($id).'" value=""/>';
    } else {
        $temp .= '<input class="upload-input-text" name="'.esc_attr($id).'" value="'.esc_attr($file).'"/><br />';
        $temp .= '<a href="'. esc_attr($file) . '" target="_blank">';
        $temp .= '<img src="'.esc_attr($file).'" width="200" height="200" alt="" />';
        $temp .= '</a>';
    }

    return $temp;
}

/**
* Save the options
*/
function jc_options_page_save($options){

    if ( empty($_POST) || !wp_verify_nonce($_POST['nonce'],'update-options') ){
        print 'Sorry, your nonce did not verify.';
           exit;
    }

    global $jc_options;

    $save_errors = array();

    foreach ($options as $key => $option) {
        if (array_key_exists('id', $option)){
            $id = $option['id'];
            switch ($option['type']) {
                case 'set':
                    break;
                case 'text':
                case 'textarea':
                    if (isset($_REQUEST[$id])){
                        $val = stripslashes($_REQUEST[$id]);
                        jc_update_option($id, $val);
                    } else {
                        jc_delete_option($id);
                    }
                    break;
                case 'select':
                case 'checkbox':
                case 'radio':
                    // TODO multi-value
                    if (isset($_REQUEST[$id])){
                        jc_update_option($id, $_REQUEST[ $id ]);
                    } else {
                        jc_delete_option($id);
                    }
                    break;
                case 'upload':
                    if (!empty($_FILES['file_'.$id]['name'])){
                        $overrides = array('test_form' => false);
                        $file = wp_handle_upload($_FILES['file_' . $id], $overrides);
                        if (isset($file['error']))
                            $save_errors[] = $file['error'];
                        else
                            jc_update_option($id , $file['url']);
                    }elseif (isset($_REQUEST[$id])){
                        jc_update_option($id , $_REQUEST[$id]);
                    }
                    break;
            }
        }
    }

    update_option('jc_theme_options', $jc_options);

    return $save_errors;
}

/*
* Initialize themes options with default values.
*/
function jc_init_default_themes_options($force=false){

    global $jc_params;
    global $jc_options;
    jc_init_theme_options();

    if ($force) {
        $jc_options = array();
        delete_option('jc_theme_options');
    }
    foreach ($jc_params as $key => $option) {
        if (array_key_exists('id', $option)){
            if ($option['type'] != 'set'){
                $id = $option['id'];
                $default = $option['default'];
                jc_update_option($id, $default);
            }
        }
        // $current = get_option($id);
        // $debug .= $id . "=" . $current . "<br />";
    }
    update_option('jc_theme_options', $jc_options);

}

function jc_under_contruction(){
    $html = jc_get_option('uc_custom_html');
    echo html_entity_decode($html, ENT_QUOTES);
    die();
}

/*============================================================================
 Adding meta "page options" to Page/Post
============================================================================*/

add_action( 'add_meta_boxes', 'jc_page_options_add_meta_box' );
function jc_page_options_add_meta_box(){
    add_meta_box("page_options_meta_box", __('Page Options', 'jc-one-lite'), "jc_page_options_meta_box", 'page', "normal", "low");
    add_meta_box("page_options_meta_box", __('Page Options', 'jc-one-lite'), "jc_page_options_meta_box", 'post', "normal", "low");

}
function jc_page_options_meta_box(){

    global $post;

    wp_nonce_field(plugin_basename( __FILE__ ), 'jc_nonce');

    $sidebar = get_post_meta($post->ID, "_sidebar", true);
    if (!isset($sidebar)) $sidebar = "1";
    ?>
    <p>
        <label><strong><?php echo __('Display the sidebar', 'jc-one-lite'); ?></strong> :</label><br />
        <select name="_sidebar" style="width: 98%;">
            <option value="0" <?php if ($sidebar == "0") echo 'selected="selected"'; ?> ><?php _e('No', 'jc-one-lite'); ?></option>
            <option value="1" <?php if ($sidebar != "0") echo 'selected="selected"'; ?> ><?php _e('Yes', 'jc-one-lite'); ?></option>
        </select>
    </p>
    <?php
}

add_action('save_post', 'jc_page_options_save_details');
function jc_page_options_save_details($post_id){

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    if (!(isset($_POST['jc_nonce']) && wp_verify_nonce($_POST['jc_nonce'], plugin_basename(__FILE__)))) return $post_id;

    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) return $post_id;
    } else {
        if (!current_user_can('edit_post', $post_id)) return $post_id;
    }

    $fields = array(
        '_sidebar',
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $data = $_POST[$field];
            update_post_meta($post_id, $field, $data);
        }
    }

}

