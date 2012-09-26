<?php
/**
 * Functions - general template functions that are used throughout WP Framework
 */

/**
 * framework_media() loads javascripts and styles
 *
 * @since 0.2.3
 */
function framework_media() {
	if( is_admin() ) return;
	wp_enqueue_script( 'modernizr', JS . '/modernizr.js', array() );	
	wp_enqueue_script( 'combo', JS . '/combo.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'screen_js', JS . '/screen.js', array( 'jquery' ), false, true );
}

/**	//////////// Custom Menus ////////////
 * register_redline_menus() registers custom menus in admin panel
 *
 * @Redline uses two custom menus
 * @since 0.8
 */
function register_redline_menus() {
	register_nav_menus(
		array(
			'primary'   => __( 'Primary Menu', 'redline' ),
			'secondary' => __( 'Secondary Menu', 'redline' )
		)
	);
}

/**
 * prim_redline_nav() defines Primary menu /Pages/
 *
 * @Redline uses this menu above header image.
 * @ since 0.8
 */
function prim_redline_nav() {
	if ( function_exists( 'wp_nav_menu' ) )
		wp_nav_menu( 'menu_class=nav&container_class=menu&fallback_cb=prim_redline_nav_fallback&theme_location=primary' );
	else
		prim_redline_nav_fallback();
}

function prim_redline_nav_fallback() {
	wp_page_menu( 'show_home=1' );
}

/**
 * sec_redline_nav() defines Secondary menu /Categories/
 *
 * @Redline uses this menu below header image.
 * @ since 0.8
 */
function sec_redline_nav() {
	if ( function_exists( 'wp_nav_menu' ) )
		wp_nav_menu( 'menu_class=nav&container_id=catg&fallback_cb=redline_wp_list_categories&theme_location=secondary' );
	else
		redline_wp_list_categories();
}

function redline_wp_list_categories() {
?><div id="catg"><ul class="nav"><?php wp_list_categories( 'exclude=1&title_li' ); ?></ul></div><?php
}	//////////// End Custom Menus ////////////

/**
 * remove_generator_link() Removes generator link
 */
function remove_generator_link() { return ''; }

/**
 * framework_menu - adds css class to the <ul> tag in wp_page_menu.
 *
 * @since 0.3
 * @filter framework_menu_ulclass
 * @needsdoc
 */
function framework_menu_ulclass( $ulclass ) {
	$classes = apply_filters( 'framework_menu_ulclass', (string) 'nav' ); // Available filter: framework_menu_ulclass
	return preg_replace( '/<ul>/', '<ul class="'. $classes .'">', $ulclass, 1 );
}

/**
 * framework_nice_terms clever terms
 *
 * @since 0.2.3
 * @needsdoc
 */
function framework_nice_terms( $term = '', $normal_separator = ', ', $penultimate_separator = ' and ', $end = '' ) {
	if ( !$term ) return;
	switch ( $term ):
		case 'cats':
			$terms = framework_get_terms( 'cats', $normal_separator );
			break;
		case 'tags':
			$terms = framework_get_terms( 'tags', $normal_separator );
			
			break;
	endswitch;
	if ( empty($term) ) return;
	$things = explode( $normal_separator, $terms );
	
	$thelist = '';
	$i = 1;
	$n = count( $things );
		
	foreach ( $things as $thing ) {
		
		$data = trim( $thing, ' ' );
		
		$links  = preg_match( '/>(.*?)</', $thing, $link );
		$hrefs  = preg_match( '/href="(.*?)"/', $thing, $href );
		$titles = preg_match( '/title="(.*?)"/', $thing, $title );
		$rels   = preg_match( '/rel="(.*?)"/', $thing, $rel );
		
		if (1 < $i and $i != $n) {
			$thelist .= $normal_separator;
		}

		if (1 < $i and $i == $n) {
			$thelist .= $penultimate_separator;
		}
		$thelist .= '<a rel="'. $rel[1] .'" href="'. $href[1] .'"';
		if ( !$term = 'tags' )
			$thelist .= ' title="'. $title[1] .'"';
		$thelist .= '>'. $link[1] .'</a>';
		$i++;
	}
	$thelist .= $end;
	return apply_filters( 'framework_nice_terms', (string) $thelist );
}

/**
 * framework_get_terms() Returns other terms except the current one (redundant)
 *
 * @since 0.2.3
 * @usedby framework_entry_footer()
 */
function framework_get_terms( $term = NULL, $glue = ', ' ) {
	if ( !$term ) return;
	
	$separator = "\n";
	switch ( $term ):
		case 'cats':
			$current = single_cat_title( '', false );
			$terms = get_the_category_list( $separator );
			break;
		case 'tags':
			$current = single_tag_title( '', '',  false );
			$terms = get_the_tag_list( '', "$separator", '' );
			break;
	endswitch;
	if ( empty($terms) ) return;
	
	$thing = explode( $separator, $terms );
	foreach ( $thing as $i => $str ) {
		if ( strstr( $str, ">$current<" ) ) {
			unset( $thing[$i] );
			break;
		}
	}
	if ( empty( $thing ) )
		return false;

	return trim( join( $glue, $thing ) );
}

/**
 * framework_get Gets template files
 *
 * @since 0.2.3
 * @needsdoc
 * @action framework_get
 * @todo test this on child themes
 */
function framework_get( $file = NULL ) {
	do_action( 'framework_get' ); // Available action: framework_get
	$error = "Sorry, but <code>{$file}</code> does <em>not</em> seem to exist. Please make sure this file exist in <strong>" . get_stylesheet_directory() . "</strong>\n";
	$error = apply_filters( 'framework_get_error', (string) $error ); // Available filter: framework_get_error
	if ( isset( $file ) && file_exists( get_stylesheet_directory() . "/{$file}.php" ) )
		locate_template( get_stylesheet_directory() . "/{$file}.php" );
	else
        echo $error;
}

/**
 * include_all() A function to include all files from a directory path
 *
 * @since 0.2.3
 * @credits k2
 */
function include_all( $path, $ignore = false ) {

	/* Open the directory */
	$dir = @dir( $path ) or die( 'Could not open required directory ' . $path );
	
	/* Get all the files from the directory */
	while ( ( $file = $dir->read() ) !== false ) {
		/* Check the file is a file, and is a PHP file */
		if ( is_file( $path . $file ) and ( !$ignore or !in_array( $file, $ignore ) ) and preg_match( '/\.php$/i', $file ) ) {
			require_once( $path . $file );
		}
	}
	$dir->close(); // Close the directory, we're done.
}

/**
 * header_style() Styling the custom header
 *
 */
function header_style() {
?>
<style type="text/css">
#logo { background: #fff url(<?php header_image(); ?>) bottom center no-repeat;
height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}
	<?php
		//  Has the text been hidden?
		//  If so, set display to equal none
		if ( 'blank' == get_header_textcolor() ) { ?>
		  #blog_header a { display: none; }

		<?php } else {
		  //  Otherwise, set the color to be the user selected one
		?>
			#blog_header a { color: #<?php header_textcolor();?>; }

		<?php } ?>

</style>
<?php
}

function admin_header_style() {
?>
<style type="text/css">
#headimg { background: #fff url(<?php header_image() ?>) bottom center no-repeat;
height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
text-align: center; min-height: 85px !important;
}

#headimg h1 { font-size: 48px; line-height: 1.5em; font-weight:bold; text-align:center; font-family: "Trebuchet MS", "DejaVu Sans Serif", sans-serif; margin:0; }
#headimg h1 a { text-decoration:none; }

#desc { display: none; }
</style>
<?php
}

if ( function_exists( 'add_custom_image_header' ) ) {
	add_custom_image_header( 'header_style', 'admin_header_style' );
}


/**
 * redline_credits() Echos credits link
 *
 * @since 0.2
 * @filter redline_credits
 */
function redline_credits( $sep = ' &#124; ' ) {
	$credits = 'Thanks to <a href="http://wordpress.org/">WordPress</a>'. $sep .'Design by <a href="http://post-scriptum.info/">yul.yordanov</a>';
	echo apply_filters( 'redline_credits', (string) $credits );
}

?>