<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" >

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="generator" content="WordPress" />
	<!--
	+-+-+-+-+-+-+-+-+-+ +-+-+ +-+-+-+ +-+-+-+-+ 
	|W|o|r|d|P|r|e|s|s| |i|s| |t|h|e| |b|e|s|t| 
	+-+-+-+-+-+-+-+-+-+ +-+-+ +-+-+-+ +-+-+-+-+ -->
		
	<title><?php semantic_title(); ?></title>
	
	<link rel="profile" href="http://purl.org/uF/hAtom/0.1/" />
	<link rel="profile" href="http://purl.org/uF/2008/03/" />
	
	<!-- Stylesheets -->
	<link rel="stylesheet" href="<?php echo get_stylesheet_uri() ?>" type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="<?php echo CSS . '/print.css'; ?>" type="text/css" media="print" />
	
	<!-- Links: RSS + Atom Syndication + Pingback etc. -->
	<link rel="alternate" href="<?php echo get_feed_link( 'atom' ); ?>" type="application/atom+xml" title="Sitewide ATOM Feed" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<!--	Theme Hook
		(Place any custom script and code here or bellow)
	-->
	<?php if ( is_singular() && get_option( 'thread_comments' ) ) 
		wp_enqueue_script( 'comment-reply' ); // loads the javascript required for threaded comments
	wp_head(); ?>
</head><!--END head-->

<!--BEGIN body-->
<body <?php body_class(); ?>>

	<!--Begin infoarea-->
	<section id="siteinfo"><div id="feedarea">
	<dl><dt><span style="float:left;"><?php bloginfo( 'description' ) ?></span>
		<?php echo date_i18n( __( 'l, j F Y - G:i', 'redline' ) ); ?></dt>
	</dl></div></section>
	<!--End infoarea -->

	<!--BEGIN #container-->
	<div id="container">
	
	<!--BEGIN .header-->
	<div id="header">
	
		<div id="topmenu" role="navigation"> 
			<!--Primary menu /Pages/--><nav id="prim_nav"><?php prim_redline_nav(); ?></nav><!--END Primary menu /Pages/--> 
		</div><!--END #topmenu-->
		
		<!--Logo-->
		<header role="banner"><div id="logo">
		<h1 id="blog_header"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ) ?></a></h1></div></header><!--End Logo-->

		<!--Secondary menu-->
		<div id="sec_nav" role="navigation"><?php sec_redline_nav(); ?></div>
		
	</div><!--END .header-->
	
	<!--BEGIN #content-->
	<div id="content">
