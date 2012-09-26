<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link title="RSS 2.0" type="application/rss+xml" href="<?php bloginfo('rss2_url'); ?>" rel="alternate" />
<!--[if IE 6]>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/DD_belatedPNG.js"></script>
<script>DD_belatedPNG.fix('.nav li,.searchsm'); </script> 
<![endif]-->
<?php if(is_singular()) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>

<body>

   <div id="fullwrapper">
       <div class="wrap">
           <div class="header">
               <div class="logo"><p><h3><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h3></p><?php bloginfo('description'); ?></div>
               	 <ul class="nav">
                 		<li><a title="<?php _e('Home', 'default'); ?>" href="<?php echo get_settings('home'); ?>/"><?php _e('Home', 'default'); ?></a></li>
	<?php wp_list_pages('title_li=&depth=-1'); ?>
                </ul>
           </div>
       </div>
       <div class="wrap">