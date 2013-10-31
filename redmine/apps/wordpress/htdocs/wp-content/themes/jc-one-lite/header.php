<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php
    wp_head();
    ?>
</head>
<body <?php body_class(jc_body_class()); ?>>
    <div id="wrapper">
    <header class="main">
        <hgroup>
            <?php
            $header_image = get_header_image();
            $heading_tag = (is_home() || is_front_page()) ? 'h1' : 'div';
            $heading_logo = jc_get_option('logo_image');

            // WP custom headers
            if ( ! empty( $header_image ) ) { ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></a>
            <?php
            } else if ($heading_logo){
                // Old theme custom headers
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
