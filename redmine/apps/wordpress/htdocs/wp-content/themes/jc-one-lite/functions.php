<?php

define('JC_OPTIONS_THEME', 'jc_one_lite');

// themes options
$jc_options = array();
// themes params
$jc_params = array();

require_once(get_template_directory().'/include/_params.php');
require_once(get_template_directory().'/include/admin.php');

if (!isset( $content_width)) $content_width = 860;


add_action('after_setup_theme', 'jc_one_setup');
if (! function_exists('jc_one_setup')){

    function jc_one_setup() {
        
        // load theme options
        jc_load_options();

        // Loading languages 
        load_theme_textdomain('jc-one-lite', get_template_directory() . '/languages');

        // Add custom post type
        jc_one_custom();

        // Add styles for the visual editor
        add_editor_style();

        // Add default posts and comments RSS feed links to <head>.
        add_theme_support('automatic-feed-links');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menu('primary', __('Primary Menu', 'jc-one-lite'));

        // Add support for custom backgrounds
        add_custom_background();

        // Add support for Featured Images
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(200, 100, true);
        add_image_size('large-feature', 500, 375, true); // Used for large feature (header) images
        

        // set the good content width
        if (jc_get_option('sidebar') != 'no'){
            global $content_width;
            $content_width = 560;
        }

        // Add Shortcodes
        if (jc_get_option('sc') != 'no'){
            require_once(get_template_directory().'/include/shortcode.php');
            // Enable Shortcodes in excerpts and widgets
            add_filter('widget_text', 'do_shortcode');
            add_filter('the_excerpt', 'do_shortcode');
            add_filter('get_the_excerpt', 'do_shortcode');
        }

        if (jc_get_option('sc_wpauto') != 'no'){
            remove_filter('the_content', 'wpautop');
            remove_filter('the_content', 'wptexturize');
        } else {
            remove_filter('the_content', 'wpautop');
            remove_filter('the_content', 'wptexturize');
            add_filter('the_content', 'jc_sc_wpautop', 99);
            add_filter('widget_text', 'jc_sc_wpautop', 99);
            add_filter('the_excerpt', 'jc_sc_wpautop', 99);
            add_filter('get_the_excerpt', 'jc_sc_wpautop', 99);
        }
        
        if (is_admin()){
            add_action('init', 'jc_init_theme_options');
            add_action('admin_menu', 'jc_theme_menu');
            add_action('admin_menu', 'jc_admin_load');
            add_action('admin_menu', 'jc_admin_resources');
        
            if (is_admin() && isset($_GET['activated'])) {
                jc_init_default_themes_options(true);
            }
        } else {
            add_action('wp_enqueue_scripts', 'jc_load_js');
            add_action('wp_head', 'jc_wp_head');
            add_action('wp_footer', 'jc_wp_footer');
        }

        // Under Construction Feature
        global $pagenow;
        if (!(is_admin() || $pagenow == 'wp-login.php')){
            if (!is_user_logged_in()) {
                if (get_option('under_contruction') == 'yes'){
                    add_action('template_redirect', jc_under_contruction());
                }
            }
        }

    }
} // jc_one_setup


function jc_load_options() {
    
    global $jc_options;
    
    if (count($jc_options) == 0){
        // load theme options
        $jc_options = get_option('jc_theme_options', false);
        if (!is_array($jc_options) || (is_array($jc_options) && count($jc_options) == 0)) {
            jc_init_default_themes_options();
        }
    }

}

function jc_get_option($key, $default=false) {

    global $jc_options;

    $ret = $default;
    if (is_array($jc_options) && array_key_exists($key, $jc_options)){
        $ret = $jc_options[$key];
    }

    return $ret; 
}

function jc_add_option($key, $value) {
    return jc_update_option($key, $value);
}

function jc_update_option($key, $value) {
    global $jc_options;
    if (is_array($jc_options)){
        $jc_options[$key] = $value;
        return true;
    }
    return false;
}

function jc_delete_option($key) {
    global $jc_options;
    if (is_array($jc_options) && array_key_exists($key, $jc_options)){
        unset($jc_options[$key]);
        return  true;
    }
    return false;
}

function jc_sc_wpautop($content) {
	$new_content = '';
	$pattern_full = '{(\[raw\].*?\[/raw\])}is';
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
	foreach ($pieces as $piece) {
		if (preg_match($pattern_contents, $piece, $matches)) {
			$new_content .= $matches[1];
		} else {
			$new_content .= wptexturize(wpautop($piece));
		}
	}
	return $new_content;
}


/**
 * Add Static Javascript And CSS Library
 */
function jc_load_js(){
    // Enqueue Javascript
    if(!is_admin()) {
        wp_enqueue_script('jquery');
        if (file_exists(get_template_directory()."/js/libs/modernizr-2.0.6.min.js")) {
            wp_enqueue_script('jc-one-modernizr', get_template_directory_uri() . '/js/libs/modernizr-2.0.6.min.js', array('jquery'));
        }
        if (file_exists(get_template_directory()."/js/plugins.js")) {
            wp_enqueue_script('jc-one-plugins', get_template_directory_uri() . '/js/plugins.js', array('jquery', 'jc-one-modernizr'));
        }
        if (file_exists(get_template_directory()."/js/script.js")) {
            wp_enqueue_script('jc-one-script', get_template_directory_uri() . '/js/script.js', array('jquery', 'jc-one-modernizr'));
        }
        if (file_exists(get_template_directory()."/styles/".jc_get_option('stylesheet'))) {
            wp_enqueue_style('jc-one-custom-style', get_template_directory_uri()."/styles/".jc_get_option('stylesheet'));
        }
    }
}

/**
 * Add Custom Javascript And CSS in head
 */
function jc_wp_head(){

    $css = jc_get_option('css');
    $js = jc_get_option('js');
    
    $out = '';
    if ($css != '') $out .= '<style type="text/css">'."\n".$css."\n".'</style>'."\n";
    if ($js != '') $out .= '<script type="text/javascript">'."\n".'/* <![CDATA[ */'."\n".$js."\n".'/* ]]> */'."\n</script>\n";

    echo $out;
}


/**
 * Add Custom Javascript in footer
 */
function jc_wp_footer(){
?>
<!--[if lt IE 7 ]>
    <script src="https://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
<![endif]-->
<?php
}


/**
 * Add support for custom post type.
 *
 * Load php file located in the types directory.
 */
function jc_one_custom(){   
    
    $custom_post_path = get_template_directory() . "/types/";
    if (!is_dir($custom_post_path)) return;
        
    if ($custom_post_dir = opendir($custom_post_path)) {
        while (($custom_post_file = readdir($custom_post_dir)) !== false) {
            if (preg_match("#\.php$#i", $custom_post_file)){
                $custom_post_type = substr($custom_post_file, 0, -4);
                if (jc_get_option($custom_post_type) != 'no'){
                    require_once($custom_post_path.$custom_post_file);
                }
            }
        }
    }
    
}


/**
 * Register sidebar
 */
function jc_register_sidebar() {

    if (jc_get_option('sidebar') != 'no'){
        register_sidebar(array(
            'name' => __('Main Sidebar', 'jc-one-lite'),
            'id' => 'primary',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => "</aside>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
       ));
    }
    
    if (jc_get_option('footer_sidebar') != 'no'){
        register_sidebar(array(
            'name' => __('Footer Sidebar 1', 'jc-one-lite'),
            'id' => 'footer_1',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => "</aside>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
       ));

        register_sidebar(array(
            'name' => __('Footer Sidebar 2', 'jc-one-lite'),
            'id' => 'footer_2',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => "</aside>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
       ));

        register_sidebar(array(
            'name' => __('Footer Sidebar 3', 'jc-one-lite'),
            'id' => 'footer_3',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => "</aside>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
       ));

    }
}
add_action('widgets_init', 'jc_register_sidebar');


/**
 * Test if a sidebar is activated
 *
 * 4 sidebars can be used in the theme, in 2 locations : 1 in sidebar / 3 in footer
 * Test if the sidebars are activated for a single post/page or is empty
 *
 * @param string $context the location : sidebar or footer
 * @return boolean if there is a sidebar to display
 */
function jc_has_sidebar($context) {
    
    global $post;
    
    if (is_page() || is_single()) {
        $sidebar = get_post_meta($post->ID, '_sidebar', true);
        if (isset($sidebar) && $sidebar == "0")
            return false;
    }
    
    if ($context == 'primary') return (jc_get_option('sidebar') == 'yes' && is_active_sidebar('primary'));
    if ($context == 'footer') 
        return (jc_get_option('footer_sidebar') == 'yes' && 
            (is_active_sidebar('footer_1')
            || is_active_sidebar('footer_2')
            || is_active_sidebar('footer_3')));
    
    return false;
}



/**
 * Display social icons in the footer.
 *
 */
function jc_one_get_social($before='<ul class="social-bar clearfix">', $after='</ul>') {
    
    $out = '';
    $socials = array (
        'twitter' => 'Twitter',
        'facebook' => 'Facebook',
        'myspace' => 'MySpace',
        'flickr' => 'Flickr',
        'skype' => 'Skype',
        'youtube' => 'Youtube',
        'vimeo' => 'Vimeo',
        'dailymotion' => 'Daily Motion'
    );    
    foreach ($socials as $sid => $sname) {
        $temp = jc_get_option($sid);
        if (isset($temp) && trim($temp) != ''){
            $out .= '<li class="social-'.$sid.'"><a href="'.esc_url($temp).'" target="_blank">'.$sname.'</a></li>';            
        }
    }
    
    if ($out != ''){
        $out = $before.$out.$after;
    }
    
    return $out;
}



        
/**
 * Sets the post excerpt length to 50 words.
 *
 */
function jc_one_excerpt_length($length) {
    return 50;
}
add_filter('excerpt_length', 'jc_one_excerpt_length');

function jc_one_get_the_excerpt($length=100, $end=' ...') {
    $out = get_the_excerpt();
    if (strlen($out) > $length)
        $out = substr($out, 0, $length).$end;
        
    return $out;
}



/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function jc_one_page_menu_args($args) {
    $args['show_home'] = true;
    return $args;
}
add_filter('wp_page_menu_args', 'jc_one_page_menu_args');


/**
 * Display navigation to next/previous pages when applicable
 */
function jc_one_content_nav($nav_id, $context='loop') {
    global $wp_query;
    
    if ($context == 'loop'){
        if ($wp_query->max_num_pages > 1) { ?>
        <nav id="<?php echo $nav_id; ?>" class="clearfix">
            <div class="nav-previous"><?php previous_posts_link(__('<span class="meta-nav">&larr;</span> Newer posts', 'jc-one-lite')); ?></div>
            <div class="nav-next"><?php next_posts_link(__('Older posts <span class="meta-nav">&rarr;</span>', 'jc-one-lite')); ?></div>
        </nav>
        <?php }
    } else {
        ?>
        <nav id="<?php echo $nav_id; ?>" class="clearfix">
            <div class="nav-previous"><?php previous_post_link('%link', __('<span class="meta-nav">&larr;</span> %title', 'jc-one-lite')); ?></div>
            <div class="nav-next"><?php next_post_link('%link', __('%title <span class="meta-nav">&rarr;</span>', 'jc-one-lite')); ?></div>
        </nav>
        <?php 
    }
    
}


/**
 * Display A non results block in an empty categorie, tag, search, ...
 */
function jc_one_no_results($message = '') {
    
    if ($message == '')
        $message = __('Apologies, but no results were found. Perhaps searching will help find a related post.', 'jc-one-lite');
    ?>
    <article id="post-0" class="post no-results not-found">
        <header class="entry-header">
            <h1 class="entry-title"><?php _e('Nothing Found', 'jc-one-lite'); ?></h1>
        </header><!-- .entry-header -->
        <div class="entry-content">
            <p><?php echo $message; ?></p>
            <?php get_search_form(); ?>
        </div><!-- .entry-content -->
    </article><!-- #post-0 -->
    <?php
}


if (! function_exists('jc_one_comment')) {
    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own jc_one_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     */
    function jc_one_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
        ?>
        <div class="post pingback">
            <p><?php _e('Pingback:', 'jc-one-lite'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('Edit', 'jc-one-lite'), '<span class="edit-link">', '</span>'); ?></p>
        <?php
                break;
            default :
        ?>
        <div <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
            <article id="comment-<?php comment_ID(); ?>" class="comment">
                <?php
                $avatar_size = 64;
                if ($depth != 1)
                    $avatar_size = 32;
                ?>
                <div class="comment-avatar-<?php echo $avatar_size; ?>">
                <?php
                echo get_avatar($comment, $avatar_size);
                ?>
                </div>
                <div class="comment-body">
                    <div class="comment-meta">
                        <div class="comment-author vcard">
                            <?php
                                /* translators: 1: comment author, 2: date and time */
                                printf(__('%1$s <span class="datetime">&mdash; %2$s</span>', 'jc-one-lite'),
                                    sprintf('<span class="fn">%s</span>', get_comment_author_link()),
                                    sprintf('<time pubdate datetime="%2$s">%3$s</time>',
                                        esc_url(get_comment_link($comment->comment_ID)),
                                        get_comment_time('c'),
                                        /* translators: 1: date, 2: time */
                                        sprintf(__('%1$s at %2$s', 'jc-one-lite'), get_comment_date(), get_comment_time())
                                   )
                               );
                            ?>

                            <?php edit_comment_link(__('Edit', 'jc-one-lite'), '<span class="edit-link">', '</span>'); ?>
                        </div><!-- .comment-author .vcard -->
                        <?php if ($comment->comment_approved == '0') : ?>
                            <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'jc-one-lite'); ?></em>
                        <?php endif; ?>
                    </div>

                    <div class="comment-content"><?php comment_text(); ?></div>

                    <div class="reply">
                        <?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply <span>&darr;</span>', 'jc-one-lite'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                    </div><!-- .reply -->
                </div>
            </article><!-- #comment-## -->

        <?php
                break;
        endswitch;
    }
} // ends check for jc_one_comment()

