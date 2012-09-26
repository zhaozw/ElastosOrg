<?php

function jc_init_theme_options(){

    global $jc_params;
    $theme = JC_OPTIONS_THEME;
    
    // css
    $alt_stylesheet_path = get_template_directory() . '/styles/';
    $alt_stylesheets = array();
    if ( is_dir($alt_stylesheet_path) ) {
        if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) {
            while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
                if (preg_match("#\.css$#i", $alt_stylesheet_file)){
                    $name = str_replace('.css', '', $alt_stylesheet_file);
                    $alt_stylesheets[] = array(
                        "name" => $name,
                        "value" => $alt_stylesheet_file);
                }
            }
        }
    }


    $options = array();

    $options[] = array(
        'id' => 'general_settings',
        'name' => __('General Settings', 'jc-one-lite'),
        'type' => 'set',
        'help' => ''
    );
    
    $options[] = array(
        'id' => 'stylesheet',
        "name" => __("Theme Stylesheet", 'jc-one-lite'),
        "help" => __("Select your themes alternative color scheme.", 'jc-one-lite'),
        "default" => "default.css",
        "type" => "select",
        "choices" => $alt_stylesheets
    );
    $options[] = array(
        'id' => 'css',
        'name' => __('Custom CSS', 'jc-one-lite'),
        'help' => __('Custom CSS code', 'jc-one-lite'),
        'default' => '',
        'type' => 'textarea',
        'options' => array(
            'style' => 'style="width: 98%; height: 100px;"'
        )
    );
    $options[] = array(
        'id' => 'js',
        'name' => __('Custom JS', 'jc-one-lite'),
        'help' => __('Custom JavaScript code', 'jc-one-lite'),
        'default' => '',
        'type' => 'textarea',
        'options' => array(
            'style' => 'style="width: 98%; height: 75px;"'
        )
    );
    $options[] = array(
        'id' => 'sidebar',
        'name' => __('Sidebar ?', 'jc-one-lite'),
        'help' => __('Display a sidebar or not ?', 'jc-one-lite'),
        'default' => 'yes',
        'type' => 'select',
        'choices' => array(
            array( 'name' => __('no', 'jc-one-lite'), 'value' => 'no'),
            array( 'name' => __('yes', 'jc-one-lite'), 'value' => 'yes'),
        )
    );
    $options[] = array(
        'id' => 'search',
        'name' => __('Search Box ?', 'jc-one-lite'),
        'help' => __('Display a search box in the nav ?', 'jc-one-lite'),
        'default' => 'yes',
        'type' => 'select',
        'choices' => array(
            array( 'name' => __('no', 'jc-one-lite'), 'value' => 'no'),
            array( 'name' => __('yes', 'jc-one-lite'), 'value' => 'yes'),
        )
    );
    
    $options[] = array(
        'id' => 'header_settings',
        'name' => __('Header settings', 'jc-one-lite'),
        'type' => 'set',
        'help' => ''
    );
    $options[] = array(
        'id' => 'logo_image',
        'name' => __('Custom Logo Image', 'jc-one-lite'),
        'help' => __('Upload an image for the logo, at the top center of the page, or specify the image address of your online image. (http://yoursite.com/logo.png)', 'jc-one-lite'),
        'default' => '',
        'type' => 'upload'
    );
    $options[] = array(
        'id' => 'logo_image_css',
        'name' => __('CSS Placement for Logo', 'jc-one-lite'),
        'help' => __('Css for the logo. Don\'t use the url() statement which is automatically define', 'jc-one-lite'),
        'default' => '',
        'type' => 'text',
        'options' => array(
            'style' => 'style="width: 98%;"'
        )
    );
    $options[] = array(
        'id' => 'logo_image_height',
        'name' => __('Logo\'s height', 'jc-one-lite'),
        'help' => __('Height of the logo (in px).', 'jc-one-lite'),
        'default' => '',
        'type' => 'text',
        'options' => array(
            'style' => 'style="width: 100px;"'
        )
    );
    
    
    $options[] = array(
        'id' => 'footer_settings',
        'name' => __('Footer settings', 'jc-one-lite'),
        'type' => 'set',
        'help' => ''
    );
    $options[] = array(
        'id' => 'footer_sidebar',
        'name' => __('Sidebar ?', 'jc-one-lite'),
        'help' => __('Display a sidebar or not in the footer ?', 'jc-one-lite'),
        'default' => 'no',
        'type' => 'select',
        'choices' => array(
            array( 'name' => __('no', 'jc-one-lite'), 'value' => 'no'),
            array( 'name' => __('yes', 'jc-one-lite'), 'value' => 'yes'),
        )
    );
    $options[] = array(
        'id' => 'footer_content',
        'name' => __('Custom Footer Content', 'jc-one-lite'),
        'help' => __('Html Code For the extra footer content', 'jc-one-lite'),
        'default' => '',
        'type' => 'textarea',
        'options' => array(
            'style' => 'style="width: 98%; height: 100px;"'
        )
    );
    $options[] = array(
        'id' => 'footer_copyright',
        'name' => __('Copyright', 'jc-one-lite'),
        'help' => __('Display a custom copyright', 'jc-one-lite'),
        'default' => '<a href="http://www.madeby-jc.com/" target="_blank">made by JC</a>',
        'type' => 'text',
        'options' => array(
            'style' => 'style="width: 98%;"'
        )
    );
    
    // Under Construction Settings
    $options[] = array(
        'id' => 'under_construction_settings',
        'name' => __('Under Construction Settings', 'jc-one-lite'),
        'type' => 'set',
        'help' => ''
    );
    $options[] = array(
        'id' => 'under_contruction',
        'name' => __('Is under contruction', 'jc-one-lite'),
        'help' => __('Is the website under contruction.<br />note: soon you signin, you can see your site website.', 'jc-one-lite'),
        'default' => 'no',
        'type' => 'select',
        'choices' => array(
            array( 'name' => __('no', 'jc-one-lite'), 'value' => 'no'),
            array( 'name' => __('yes', 'jc-one-lite'), 'value' => 'yes'),
        )
    );
    $options[] = array(
        'id' => 'uc_custom_html',
        'name' => __('Custom Content', 'jc-one-lite'),
        'help' => __('Html Code For the custom under contruction page.<br /> Contains all the html code for the page.', 'jc-one-lite'),
        'default' => "<html>\n<head>\n<title>Under Construction</title>\n"
            .'<link rel="stylesheet" media="all" href="'.get_bloginfo( 'stylesheet_url' ).'" />'
            ."<body id='under-construction'>"
            ."<div id='message'>\n"
            .'<h1>'.get_bloginfo( 'name' ).' '.__("under Construction", 'jc-one-lite').'</h1>'."\n"
            .'<p>'.__("Sorry, but the site is under contruction...", 'jc-one-lite').'</p>'."\n"
            ."</div>\n"
            ."</body>\n</html>",
        'type' => 'textarea',
        'options' => array(
            'style' => 'style="width: 98%; height: 200px;"'
        )
    );
    
    $options[] = array(
        'id' => 'social',
        'name' => __('Social Networks', 'jc-one-lite'),
        'type' => 'set',
        'help' => 'Just set the url profile for each social networks you want.'
    );
    
    $socials = array (
        'twitter' => 'Twitter',
        'facebook' => 'Facebook',
        'myspace' => 'MySpace',
        'flickr' => 'Flickr',
        'skype' => 'Skype',
        'youtube' => 'Youtube',
        'vimeo' => 'Vimeo',
        'dailymotion' => 'DailyMotion'
    );    
    foreach ($socials as $sid => $sname) {
        $options[] = array(
            'id' => $sid,
            'name' => $sname,
            'help' => '',
            'default' => '',
            'type' => 'text',
            'options' => array(
                'style' => 'style="width: 98%;"'
            )
        );
    }
    
    $options[] = array(
        'id' => 'miscellanious',
        'name' => __('Miscellanious', 'jc-one-lite'),
        'type' => 'set',
        'help' => ''
    );
    $options[] = array(
        'id' => 'sc',
        'name' => __('Use Shortcodes', 'jc-one-lite'),
        'help' => __('Enables the plugin\'s shortcodes.', 'jc-one-lite'),
        'default' => 'yes',
        'type' => 'select',
        'choices' => array(
            array( 'name' => __('no', 'jc-one-lite'), 'value' => 'no'),
            array( 'name' => __('yes', 'jc-one-lite'), 'value' => 'yes'),
        )
    );
    $options[] = array(
        'id' => 'sc_prefixe',
        'name' => __('Prefix For Schortcode', 'jc-one-lite'),
        'help' => __('If shortcodes names are in conflict with other plugin shortcode use this field<br />Ex: with prefix &quot;custom&quot; use &quot;[custom_button ...]...[/custom_button]&quot;', 'jc-one-lite'),
        'default' => '',
        'type' => 'text',
        'options' => array(
            'style' => 'style="width: 100px;"'
        )
    );
    $options[] = array(
        'id' => 'sc_wpauto',
        'name' => __('Automatic Html correction', 'jc-one-lite'),
        'help' => __('Let Wordpress correct your Html and auto-generate &lt;p&gt; elements.<br />For advence Html editing and intense shortcodes usage, disable this options.', 'jc-one-lite'),
        'default' => 'no',
        'type' => 'select',
        'choices' => array(
            array( 'name' => __('no', 'jc-one-lite'), 'value' => 'no'),
            array( 'name' => __('yes', 'jc-one-lite'), 'value' => 'yes'),
        )
    );
    $options[] = array(
        'id' => 'fancybox',
        'name' => __('FancyBox', 'jc-one-lite'),
        'help' => __('Display image in a Mac-style "lightbox" that floats overtop of web page.', 'jc-one-lite'),
        'default' => 'yes',
        'type' => 'select',
        'choices' => array(
            array( 'name' => __('no', 'jc-one-lite'), 'value' => 'no'),
            array( 'name' => __('yes', 'jc-one-lite'), 'value' => 'yes'),
        )
    );
    
    $jc_params = $options;
    //update_option('jc_theme_options', $options);
}

