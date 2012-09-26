<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php
    global $page, $paged;
    wp_title('|', true, 'right');
    bloginfo('name');
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page()))
        echo " | $site_description";
    if ($paged >= 2 || $page >= 2)
        echo ' | ' . sprintf(__('Page %s', 'jc-one-lite'), max($paged, $page));
    ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php
    if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	wp_head();
    ?>
</head>
<body <?php body_class('layout-default'); ?>>
    <div id="wrapper">
    <header class="main">
        <hgroup>
            <?php 
            $heading_tag = (is_home() || is_front_page()) ? 'h1' : 'div'; 
            $heading_logo = jc_get_option('logo_image');
            if ($heading_logo){
                $heading_logo_css = jc_get_option('logo_image_css');
                $heading_logo_height = jc_get_option('logo_image_height');
                if ($heading_logo_height)
                    $heading_logo_height = ' height:'.$heading_logo_height.'px;';
            ?>
		    <<?php echo $heading_tag; ?> id="site-title" 
		        class="site-title-logo" ><span><a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home" style="background: url('<?php echo $heading_logo; ?>') <?php echo $heading_logo_css; ?>;<?php echo $heading_logo_height; ?>"><?php bloginfo('name'); ?></a></span></<?php echo $heading_tag; ?>>
		    <?php 
		    } else { ?>
		    <<?php echo $heading_tag; ?> id="site-title"><span><a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a></span></<?php echo $heading_tag; ?>>
		    <div id="site-description"><?php bloginfo('description'); ?></div>
		    <?php 
		    
		    }
		    ?>
	    </hgroup>
    </header>
    <div id="container">
        <nav id="access" class="selfclear search<?php echo(jc_get_option('search') != 'no' ? '-on' : '-off'); ?>" role="navigation">
		    <div class="left selfclear">
		        <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
		    </div>
		    <?php get_search_form(); ?>
		    <div class="clear"></div>
	    </nav><!-- #access -->
	    
        <div id="main" <?php if (jc_has_sidebar('primary')) { ?>class="main-sidebar selfclear"<?php } ?> role="main">
