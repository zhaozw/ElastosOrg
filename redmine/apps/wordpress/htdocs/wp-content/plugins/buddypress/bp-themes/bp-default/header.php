<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<?php if ( current_theme_supports( 'bp-default-responsive' ) ) : ?><meta name="viewport" content="width=device-width, initial-scale=1.0" /><?php endif; ?>
		<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>
<?php echo '<link rel="shortcut icon" type="image/x-icon" href="' . 'http://'.$_SERVER['SERVER_NAME']. '/favicon.ico">'; ?>
		<?php do_action( 'bp_head' ); ?>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?> id="bp-default">

		<?php do_action( 'bp_before_header' ); ?>

		<div id="header" style="background:#f4f4f4;">
			<div id="search-bar" role="search" style="display:none;">
				<div class="padder">
						<form action="<?php echo bp_search_form_action(); ?>" method="post" id="search-form">
							<label for="search-terms" class="accessibly-hidden"><?php _e( 'Search for:', 'buddypress' ); ?></label>
							<input type="text" id="search-terms" name="search-terms" value="<?php echo isset( $_REQUEST['s'] ) ? esc_attr( $_REQUEST['s'] ) : ''; ?>" />

							<?php echo bp_search_form_type_select(); ?>

							<input type="submit" name="search-submit" id="search-submit" value="<?php _e( 'Search', 'buddypress' ); ?>" />

							<?php wp_nonce_field( 'bp_search_form' ); ?>

						</form><!-- #search-form -->

				<?php do_action( 'bp_search_login_bar' ); ?>

				</div><!-- .padder -->
			</div><!-- #search-bar -->

			<div id="navigation" role="navigation">
					<h1 id="logo" role="banner"><a href="http://www.elastos.com" title="<?php _ex( 'Home', 'Home page banner link title', 'buddypress' ); ?>"><img src="http://elastos.org/wp-admin/images/wordpress-logo.png"/></a></h1>

				<?php
				/* wp_nav_menu( array( 'container' => false, 'menu_id' => 'nav', 'theme_location' => 'primary', 'fallback_cb' => 'bp_dtheme_main_nav' ) ); */

				require( ABSPATH . 'elorg_vips.php' );

				bp_has_members( 'user_id=0&type=active&per_page=' . '10' . '&max=' . '10' . '&populate_extras=0' . '&include=' . $elorg_vips );  ?>
				<div class="avatar-block" style="margin-top:11px;width:70%;float:right;border-bottom:3px solid #6fa833;">
				<?php while ( bp_members() ) : bp_the_member(); ?>
					<div class="item-avatar">
						<a href="<?php bp_member_permalink() ?>"><?php bp_member_avatar() ?></a>
					</div>
				<?php endwhile; ?>
				</div>
			</div>

			<?php do_action( 'bp_header' ); ?>

		</div><!-- #header -->

		<?php do_action( 'bp_after_header'     ); ?>
		<?php do_action( 'bp_before_container' ); ?>

	<ul id="icon">
		<li>
		<a href="http://elastos.org" title="elastosorg">
			<img src="http://elastos.org/wp-admin/images/home.png" alt="home" width="25" height="28" />
		</a>
		</li>
		<li id="iconli">
		<a href="http://elastos.org/project-guide/" title="project-guide">
			<img src="http://elastos.org/wp-admin/images/developers.png" alt="developers" width="25" height="25" />
		</a>
		</li>
	</ul>

		<div id="container">
