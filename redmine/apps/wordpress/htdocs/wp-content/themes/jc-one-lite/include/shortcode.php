<?php
/*
Plugin Name: JC Shortcode
Plugin URI: http://www.bibichette.net/jc_shortcode/
Description: Some useful shortcodes for great looking.
Version: 0.1
Author: Julien Chanseaume
Author URI: http://www.bibichette.com/
*/
/*
* shortcodes.php
*
* contains all shortcodes functions:
* - buttons
* - content boxes
* - column layouts
* - quotes
* - custom lists
* - dropcaps
* - iframes
* - codes
------------------------------------------------------------------------------*/

add_action("init", 'jc_sc_add_shortcode_support');
function jc_sc_add_shortcode_support(){

    if (is_admin()) {
        // TODO add init function when theme is activated or updated
        // jc_sc_init_shortcode_options();
    } else {
        // TODO: test shortcode prefixe
        $sc_prefixe = jc_get_option('sc_prefixe');
        if (!empty($sc_prefixe)) $sc_prefixe = $sc_prefixe . '_';
        // Button
        add_shortcode($sc_prefixe.'button', 'jc_sc_button');
        // Paragraph
        add_shortcode($sc_prefixe.'p', 'jc_sc_p');
        // br
        add_shortcode($sc_prefixe.'br', 'jc_sc_br');
        // Box
        add_shortcode($sc_prefixe.'box', 'jc_sc_box');
        // Column Layout
        add_shortcode($sc_prefixe.'one_half', 'jc_sc_col_one_half');
        add_shortcode($sc_prefixe.'one_half_last', 'jc_sc_col_one_half_last');
        add_shortcode($sc_prefixe.'one_third', 'jc_sc_col_one_third');
        add_shortcode($sc_prefixe.'one_third_last', 'jc_sc_col_one_third_last');
        add_shortcode($sc_prefixe.'two_third', 'jc_sc_col_two_third');
        add_shortcode($sc_prefixe.'two_third_last', 'jc_sc_col_two_third_last');
        add_shortcode($sc_prefixe.'one_fourth', 'jc_sc_col_one_fourth');
        add_shortcode($sc_prefixe.'one_fourth_last', 'jc_sc_col_one_fourth_last');
        add_shortcode($sc_prefixe.'two_fourth', 'jc_sc_col_one_half');
        add_shortcode($sc_prefixe.'two_fourth_last', 'jc_sc_col_one_half_last');
        add_shortcode($sc_prefixe.'three_fourth', 'jc_sc_col_three_fourth');
        add_shortcode($sc_prefixe.'three_fourth_last', 'jc_sc_col_three_fourth_last');
        // Dropcap
        add_shortcode($sc_prefixe.'dropcap', 'jc_sc_dropcap');
        // Quote
        add_shortcode($sc_prefixe.'quote', 'jc_sc_quote');
        // list
        add_shortcode($sc_prefixe.'list', 'jc_sc_list');
        // jc_sc_iframe
        add_shortcode($sc_prefixe.'iframe', 'jc_sc_iframe');
        // hr
        add_shortcode($sc_prefixe.'hr', 'jc_sc_hr');
        // Lorem Ipsum Generator
        add_shortcode($sc_prefixe.'lorem', 'jc_sc_lorem_ipsum');
        // Authorize wpauto and wptexturize
        add_shortcode($sc_prefixe.'auto', 'jc_sc_auto');
        // no shortcode
        add_shortcode($sc_prefixe.'raw', 'jc_sc_raw');
        // var
        add_shortcode($sc_prefixe.'var', 'jc_sc_var');

    }
}


/**
* buttons
*
* type: button (simple colored button)
*   style: general colors
* type: icon-button (simple button with icon)
*   style:
*/

function jc_sc_button($args = '', $text= ''){

    $r = shortcode_atts(
        array(
            'link' => '',
            'icon' => '',
            'style' => '',
        ), $args );
    $out = '';
    if ($r['link'] != ''){
        $out = '<a href="'.$r['link'].'"';
        $out .= ' class="button';
        if ($r['style'])
            $out .= ' button-'.$r['style'];
        $out .= '">';
        if ($r['icon'] != '')
            $out .= '<i class="icon icon-'.$r['icon'].'"></i> ';
        $out .= $text;
        $out .= '</a>';
    }else{
        $out = $text;
    }

    return $out;
}



/*------------------------------------------------------------------------------
  generate a <P> tag (because wpautop desactivate)
------------------------------------------------------------------------------*/

function jc_sc_p( $atts, $content ){

    extract(shortcode_atts(array(
        'class' => '',
        'style' => ''
    ), $atts));

    $return = '<p';
    if ($class != "") $return .= ' class="'.$class."'";
    if ($style != "") $return .= ' style="'.$style."'";
    $return .= '>'.do_shortcode($content).'</p>';
    return $return;
}


/*------------------------------------------------------------------------------
  Box
------------------------------------------------------------------------------*/

function jc_sc_box( $atts, $content ){

    extract(shortcode_atts(array(
        'type' => 'default',
        'icon' => '',
        'class' => '',
        'style' => ''
    ), $atts));

    $return = '<div class="';
    // if ($empty == "")
    $return .= 'box ';
    if ($type != "")
        $return .= 'box-type-'.$type.' ';
    //if ($icon != "")
    //    $return .= 'box-icon box-icon-'.$icon.' ';
    if ($class != "") $return .= $class;
    $return .= '"';
    if ($style != "") $return .= ' style="'.$style.'"';
    $return .= '>';
    if ($icon != "")
        $return .= '<i class="icon icon-'.$icon.'"></i>';
    $return .= '<div class="box-content">'.do_shortcode($content).'</div></div>';
    return $return;
}


/*------------------------------------------------------------------------------
  Columns Layout
------------------------------------------------------------------------------*/

function jc_sc_col_one_half( $atts, $content = null ) {
   return '<div class="one_half">' . do_shortcode($content) . '</div>';
}
function jc_sc_col_one_half_last( $atts, $content = null ) {
   return '<div class="one_half last">' . do_shortcode($content) . '</div><div class="clearboth"></div>';
}
function jc_sc_col_one_third( $atts, $content = null ) {
   return '<div class="one_third">' . do_shortcode($content) . '</div>';
}
function jc_sc_col_one_third_last( $atts, $content = null ) {
   return '<div class="one_third last">' . do_shortcode($content) . '</div><div class="clearboth"></div>';
}
function jc_sc_col_two_third( $atts, $content = null ) {
   return '<div class="two_third">' . do_shortcode($content) . '</div>';
}
function jc_sc_col_two_third_last( $atts, $content = null ) {
   return '<div class="two_third last">' . do_shortcode($content) . '</div><div class="clearboth"></div>';
}
function jc_sc_col_one_fourth( $atts, $content = null ) {
   return '<div class="one_fourth">' . do_shortcode($content) . '</div>';
}
function jc_sc_col_one_fourth_last( $atts, $content = null ) {
   return '<div class="one_fourth last">' . do_shortcode($content) . '</div><div class="clearboth"></div>';
}
function jc_sc_col_three_fourth( $atts, $content = null ) {
   return '<div class="three_fourth">' . do_shortcode($content) . '</div>';
}
function jc_sc_col_three_fourth_last( $atts, $content = null ) {
   return '<div class="three_fourth last">' . do_shortcode($content) . '</div><div class="clearboth"></div>';
}



/*------------------------------------------------------------------------------
  Dropcast
------------------------------------------------------------------------------*/

function jc_sc_dropcap( $atts, $content ){

    extract(shortcode_atts(array(
        'color' => ''
    ), $atts));

    $return = '<span class="dropcap"';
    if ($color != "") $return .= ' style="color: '.$color.';"';
    $return .= '>'.$content.'</span>';
    return $return;
}


/*------------------------------------------------------------------------------
  Quotes:
  type: simple, double, pull
------------------------------------------------------------------------------*/

function jc_sc_quote( $atts, $content ){

    extract(shortcode_atts(array(
        'type' => 'simple',
        'width' => ''
    ), $atts));

    $return = '<blockquote class="';
    if ($type == 'double'){
        $return .= 'double">';
    } else if ($type == 'pull'){
        $return .= 'double pull"';
        if ($width != "")
            $return .= ' style="width: '.$width.'px;"';
        $return .= '>';
    } else {
        $return .= 'simple">';
    }

    $return .= do_shortcode($content).'</blockquote>';
    return $return;
}


/*------------------------------------------------------------------------------
  List
------------------------------------------------------------------------------*/

function jc_sc_list( $atts, $content ){

    extract(shortcode_atts(array(
        'type' => ''
    ), $atts));

    $return = '<div class="list';
    if ($type != "") $return .= ' list-'.$type;
    $return .= '">'.do_shortcode($content).'</div>';
    return $return;
}


/*------------------------------------------------------------------------------
  iFrame
------------------------------------------------------------------------------*/
function jc_sc_iframe( $atts, $content ){

    extract(shortcode_atts(array(
        'src' => '',
        'width' => '320',
        'height' => '240',
        'frameborder' => '0',
        'marginheight' => '0',
        'marginwidth' => '0'
    ), $atts));

    $return = '';
    if ($src != '')
        $return = '<iframe src="'.$src.'" width="'.$width.'" height="'.$height.'" frameborder="'.$frameborder.'" marginheight="'.$marginheight.'" marginwidth="'.$marginwidth.'" ></iframe>';
    return $return;
}


/*------------------------------------------------------------------------------
  Var
------------------------------------------------------------------------------*/
function jc_sc_var( $atts, $content ){

    $return = '';
    global $post;

    if (!empty($content)) {
        $return = do_shortcode(get_post_meta($post->ID, $content, true));
    }

    return $return;
}



/*------------------------------------------------------------------------------
  Some Html
------------------------------------------------------------------------------*/

function jc_sc_br( $atts, $content ){
    return '<br/>';
}

function jc_sc_hr( $atts, $content ){

    extract(shortcode_atts(array(
        'type' => ''
    ), $atts));

    $return = '<hr';
    if ($type != "") $return .= ' class="'.$type.'"';
    $return .= ' />';
    return $return;
}

/*------------------------------------------------------------------------------
  Lorem Ipsum Generator
------------------------------------------------------------------------------*/

function jc_sc_lorem_ipsum( $atts, $content ){

    extract(shortcode_atts(array(
        'length' => '100',
        'count' => '1',
        'html' => '0'
    ), $atts));

    $lorem = array(
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ullamcorper, nunc ultrices faucibus rhoncus, lectus ligula cursus lacus, eget sagittis lectus dui eu risus. Aliquam sollicitudin enim a augue aliquet at condimentum augue dictum. Nam diam turpis, convallis a tempus id, cursus ut tellus. Donec sed rhoncus velit. Vivamus ut risus purus, ac congue leo. Aenean et libero lacus, non molestie turpis. Mauris urna leo, pharetra et accumsan id, facilisis laoreet erat. Vestibulum sit amet dignissim mauris. Curabitur accumsan massa ut turpis euismod suscipit sed eu ante. Etiam non volutpat urna. In et orci eget quam congue commodo at eu diam. Nullam at dolor massa, et vehicula est. Integer pellentesque molestie facilisis. Vestibulum quis varius leo. Pellentesque in sodales ligula. Quisque imperdiet interdum bibendum.',
        'Cras viverra metus nec nunc pulvinar posuere. Suspendisse potenti. Suspendisse in purus at nibh lobortis fermentum nec sit amet justo. Curabitur libero lectus, dapibus euismod iaculis vitae, euismod eget lorem. Donec ac dolor nisi. Sed sit amet mi nisi, vitae cursus dolor. Integer et nunc quam. Praesent ultrices magna ac justo convallis molestie. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In eu massa est, vitae aliquam lacus. Nam blandit tincidunt tellus, nec tincidunt nibh euismod vel. Vestibulum ante ipsum primis in faucibus orci. In et orci eget quam congue commodo at eu diam. Nullam at dolor massa, et vehicula est. Integer pellentesque molestie facilisis. Sed gravida eros eget massa porta id vulputate sapien fringilla. Nullam risus diam, hendrerit et facilisis ut, tincidunt at odio.',
        'In tincidunt magna lacus, id accumsan ante. Praesent tempor augue ac est lobortis blandit imperdiet justo tempor. Nunc eu lobortis lorem. Aliquam dui massa, mattis et placerat sed, sollicitudin sed nibh. Nullam in metus et magna volutpat feugiat id quis urna. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aliquam bibendum neque sit amet metus lacinia interdum. Nulla neque metus, sodales tincidunt eleifend ut, sollicitudin sed quam. Curabitur bibendum varius felis at vestibulum. Sed gravida eros eget massa porta id vulputate sapien fringilla. Nunc hendrerit pellentesque urna vitae feugiat. Mauris mattis elit in eros pellentesque eu tincidunt arcu egestas. Pellentesque aliquet placerat tincidunt. Proin magna odio, aliquet non consequat non, interdum sed arcu.',
        'Etiam vulputate, nibh nec semper consectetur, elit augue interdum orci, in tempus velit massa rutrum ipsum. Etiam porta luctus urna, at aliquet magna condimentum eu. Proin ac rhoncus augue. Nunc ac malesuada dolor. Etiam non arcu ut tortor dictum facilisis ut sit amet nisl. Duis vel eros eget leo consectetur mollis vitae id nulla. Donec est augue, tristique feugiat vulputate non, blandit in sapien. Vestibulum elementum aliquam nunc varius hendrerit. Curabitur ac felis mi, vitae scelerisque mi. Nunc non odio vitae nisl tincidunt suscipit commodo at turpis. Aliquam eu tempus lorem. Mauris vel felis aliquet quam ultricies dictum a a nunc. Nulla et eros urna. Proin quis justo eu mauris faucibus feugiat. Nulla facilisi. Maecenas commodo tellus placerat felis aliquam pulvinar. Mauris mattis elit in eros pellentesque.',
        'Proin rhoncus sagittis ligula eu dignissim. Aliquam non aliquet odio. Sed lacus mi, convallis ac tincidunt non, suscipit ut orci. Morbi non ligula magna, ac volutpat nunc. Quisque congue porttitor mauris, vel posuere neque rutrum sit amet. Nunc dui justo, ullamcorper a eleifend in, sodales et leo. Cras at augue mauris, in faucibus tellus. Nullam risus diam, hendrerit et facilisis ut, tincidunt at odio. Nam urna nibh, accumsan eu congue at, malesuada a enim. Cras laoreet rhoncus tellus, nec bibendum nisi blandit vel. Duis molestie blandit semper. Aenean cursus, tellus ut iaculis tempus, sapien arcu ornare dui, faucibus interdum velit urna feugiat elit. Aenean vestibulum, enim id eleifend aliquet, nisi erat dapibus ipsum, et semper nisi tellus quis nuncet ultrices posuere cubilia luctus Curae.'
    );

    if ($length > 800) $length = 800;
    $count = intval($count);

    $out = '';
    for ($i=0 ; $i<$count ; $i++){
        if ($html != '0') $out .= '<p>';
        $out .= substr($lorem[rand(0,4)], 0, $length);
        if ($html != '0') $out .= '</p>';
        $out .= "\n";
    }

    return $out;
}

/*------------------------------------------------------------------------------
  Auto formating with wordpress
------------------------------------------------------------------------------*/

function jc_sc_auto( $atts, $content ){

    if (jc_get_option('sc_wpauto') != 'yes'){
        $return = wpautop($content);
        $return = wptexturize($return);
    }
    $return = do_shortcode($return);

    return $return;
}

/*------------------------------------------------------------------------------
  Raw: don't interpret shortcode
------------------------------------------------------------------------------*/

function jc_sc_raw( $atts, $content ){
    return $content;
}


?>
