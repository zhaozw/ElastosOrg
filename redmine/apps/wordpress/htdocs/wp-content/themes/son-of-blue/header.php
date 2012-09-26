<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
<!--[if lte IE 6]>
<style>
#c1 .content a, #c3 .content a {height: 1%;}
#masthead, #topmenu { width:expression(((document.compatMode && document.compatMode=='CSS1Compat') ? document.documentElement.clientWidth : document.body.clientWidth) < 1000 ? "1000px" : "auto");}
</style>
<![endif]-->
<!--[if lte IE 7]>
<style>
#masthead, #columnwrapper, #topmenu, #columns-bottom {zoom:1;}
</style>
<![endif]-->
</head>
<body>
<div id="masthead">
  <div id="logo"><a href="<?php echo get_option('home'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo.png" alt="" border="0" /></a></div><!-- end logo div -->
  <div id="blogtitle"><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?></div>
</div><!-- end masthead div -->
<div id="topmenu">
  <ul class="nav">
    <li class="first"><a href="<?php echo get_option('home'); ?>">Home</a></li>
	<?php wp_list_pages('sort_column=menu_order&hierarchical=0&depth=4&exclude=&title_li='); ?>
  </ul>
</div><!-- end top menu div -->