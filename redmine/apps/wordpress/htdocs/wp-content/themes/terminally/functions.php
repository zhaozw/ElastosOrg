<?php

if ( ! isset( $content_width ) ) $content_width = 550;

register_sidebar(array(
  'name' => 'sidebar',
  'id' => 'sidebar',  
	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<header><h3 class="widgettitle">',
	'after_title' => '</h3></header>',
));

add_theme_support('automatic-feed-links');
add_theme_support('custom-background');
add_editor_style();

// hack to add a class to the body tag when the sidebar is active
function terminally_has_sidebar($classes) {
	if (is_active_sidebar('sidebar')) {
		// add 'class-name' to the $classes array
		$classes[] = 'has_sidebar';		
	}
	// return the $classes array
	return $classes;
}
add_filter('body_class','terminally_has_sidebar');

?>
