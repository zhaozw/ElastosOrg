<?php
/**
 * Admin Bar
 *
 * This code handles the building and rendering of the press bar.
 */

/**
 * Instantiate the admin bar object and set it up as a global for access elsewhere.
 *
 * To hide the admin bar, you're looking in the wrong place. Unhooking this function will not
 * properly remove the admin bar. For that, use show_admin_bar(false) or the show_admin_bar filter.
 *
 * @since 3.1.0
 * @access private
 * @return bool Whether the admin bar was successfully initialized.
 */
function _wp_admin_bar_init() {
	global $wp_admin_bar;

	if ( ! is_admin_bar_showing() )
		return false;

	if ( ! strstr($_SERVER['HTTP_HOST'], DOMAIN_CURRENT_SITE) ) {
		return  false;
	}

	/* Load the admin bar class code ready for instantiation */
	require( ABSPATH . WPINC . '/class-wp-admin-bar.php' );

	/* Instantiate the admin bar */
	$admin_bar_class = apply_filters( 'wp_admin_bar_class', 'WP_Admin_Bar' );
	if ( class_exists( $admin_bar_class ) )
		$wp_admin_bar = new $admin_bar_class;
	else
		return false;

	$wp_admin_bar->initialize();
	$wp_admin_bar->add_menus();

	return true;
}
add_action( 'init', '_wp_admin_bar_init' ); // Don't remove. Wrong way to disable.

/**
 * Render the admin bar to the page based on the $wp_admin_bar->menu member var.
 * This is called very late on the footer actions so that it will render after anything else being
 * added to the footer.
 *
 * It includes the action "admin_bar_menu" which should be used to hook in and
 * add new menus to the admin bar. That way you can be sure that you are adding at most optimal point,
 * right before the admin bar is rendered. This also gives you access to the $post global, among others.
 *
 * @since 3.1.0
 */
function wp_admin_bar_render() {
	global $wp_admin_bar;

	if ( ! is_admin_bar_showing() || ! is_object( $wp_admin_bar ) )
		return false;

	do_action_ref_array( 'admin_bar_menu', array( &$wp_admin_bar ) );

	do_action( 'wp_before_admin_bar_render' );

	$wp_admin_bar->render();

	do_action( 'wp_after_admin_bar_render' );
}
add_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
add_action( 'in_admin_header', 'wp_admin_bar_render', 0 );

/**
 * Add the WordPress logo menu.
 *
 * @since 3.3.0
 */
function wp_admin_bar_wp_menu( $wp_admin_bar ) {
    $wp_admin_bar->add_menu( array(
        'id'    => 'developer',
//        'title' => '<span><img src="/elorg_common/img/programmer.jpg" style="height:26px;width:26px;"/></span>Developer',
        'title' => '<span style="font-style:italic;font-weight:bolder;background:red;">Developer</span>',
        'href'  => '/elorg_common/develop/',
        'meta'  => array(
            'title' => __('Elastos Developer'),
        ),
    ) );

    $wp_admin_bar->add_menu( array(
        'id'    => 'wp-logo',
        'title' => '<span><img src="/elorg_common/img/ElastosOrg_RedLogo.png" style="height:26px;width:26px;"/></span>',
        'href'  => 'http://elastos.org/',
        'meta'  => array(
            'title' => __('ElastosOrg Home Page'),
        ),
    ) );

$elastos_org_main_menu = '
<div id="box_menu" class="mbmenu boxMenu">
    <table style="border:0;">
        <tbody><tr>
            <td>
                <div style="height:130px"><img src="/wp-includes/images/project_management.png" alt="patapage" style="width:150px;"></div>
                <a href="/project/"><img src="/redmine/favicon.ico" style="width:16px;">Elastos Project Management</a>
                <a href="/review/"><img src="/review/favicon.ico" style="width:16px;">Code Review System</a>
                <a href="/testlink/"><img src="/testlink/gui/themes/default/images/favicon.ico" style="width:16px;">Test and Requirements</a>
                <a href="/jenkins/"><img src="/jenkins/static/d974b1d4/favicon.ico" style="width:16px;">Continuous Integration System</a>
                <a href="http://phabricator.elastos.org/login/"><img src="/wp-includes/images/phabricator-logo.png" style="width:16px;">Phabricator</a>
            </td>
            <td>
                <div style="height:130px;"><img src="/wp-includes/images/elorg_resources.png" alt="bugsvoice" style="width:200px;" style="padding-top:30px"></div>
                <a href="/wiki/"><img src="/wiki/favicon.ico" style="width:16px;">Documentation</a>
                <a href="/download/"><img src="/download/favicon.ico" style="width:16px;">Download</a>
            </td>
            <td>
                <div style="height:130px"><img src="/wp-includes/images/social-media.png" alt="bugsvoice" style="width:150px;"></div>
                <a href="http://elastos.org/members/"><img src="/wp-includes/images/thousands-of-members-icon.png" style="width:16px;">members</a>
                <a href="http://elastos.org/activity/"><img src="/wp-includes/images/MicroBLOG.ico" style="width:16px;">MicroBLOG</a>
                <a href="http://elastos.org/groups/"><img src="/wp-includes/images/elorg-groups.png" style="width:16px;">groups</a>
                <a href="http://elastos.org/blogs/"><img src="/elorg_common/img/blog.jpg" style="width:16px;">blogs</a>
                <a href="http://elastos.org/org_forums/"><img src="/wp-includes/images/bbforum.ico" style="width:16px;">org_forums</a>
            </td>
            <td>
                <div style="height:130px"><img src="/wp-includes/images/elorg_support.jpg" alt="bugsvoice" style="width:120px;"></div>
                <a href="/q2a/"><img src="/wp-includes/images/question2answer.ico" style="width:16px;">q2a</a>
                <a href="/stikked/"><img src="/stikked/favicon.ico" style="width:16px;">pastebin</a>
                <a href="/owncloud/"><img src="/owncloud/core/img/favicon.png" style="width:16px;">owncloud</a>
            </td>
        </tr>
    </tbody></table>
</div>';

$prj = '<a href="/project/"><img src="http://elastos.org/redmine/themes/gitmike/images/logo.png" style="width:20px;">Elastos Project Management</a>';
$code_view = '<a href="/review/"><img src="http://elastos.org/elorg_common/img/ElastosOrg_RedLogo.png" style="width:20px;">Code Review System</a>';
$tst = '<a href="/testlink/"><img src="http://elastos.org/elorg_common/img/ElastosOrg_RedLogo.png" style="width:20px;">Test and Requirements</a>';
$wiki = '<a href="/wiki/"><img src="http://elastos.org/elorg_common/img/ElastosOrg_RedLogo.png" style="width:20px;">Documentation</a>';
$download = '<a href="/download/"><img src="http://elastos.org/elorg_common/img/ElastosOrg_RedLogo.png" style="width:20px;">Download</a>';

$members = '<a href="/members/"><img src="http://elastos.org/elorg_common/img/ElastosOrg_RedLogo.png" style="width:20px;">members</a>';
$activity = '<a href="/activity/"><img src="http://elastos.org/elorg_common/img/ElastosOrg_RedLogo.png" style="width:20px;">activity</a>';
$groups = '<a href="/groups/"><img src="http://elastos.org/elorg_common/img/ElastosOrg_RedLogo.png" style="width:20px;">groups</a>';
$blogs = '<a href="/blogs/"><img src="http://elastos.org/elorg_common/img/ElastosOrg_RedLogo.png" style="width:20px;">blogs</a>';
$org_forums = '<a href="/org_forums/"><img src="http://elastos.org/elorg_common/img/ElastosOrg_RedLogo.png" style="width:20px;">org_forums</a>';
$q2a = '<a href="/q2a/"><img src="http://elastos.org/elorg_common/img/ElastosOrg_RedLogo.png" style="width:20px;">q2a</a>';
$community = '<div style="background-color=red; margin-left:20px;">' . $members . $activity . $groups . $blogs . $org_forums . $q2a . '</div>';

$args = array(
    'parent' => 'wp-logo',
    'id'    => 'counter',
    'title'  => 'Elastos Developer',
    'meta'  => array(
//        'html' => '<div style="width:500px;height:320px;opacity:100;background-color:lightblue;">' . $prj . $code_view . $tst . $wiki . $download . $community),
        'html' => '<div style="opacity:100;background-color:lightblue;">' . $elastos_org_main_menu),
    'href' => '/elorg_common/develop/',
);
$wp_admin_bar->add_node( $args );
return;

    if ( is_user_logged_in() ) {
        // Add "About WordPress" link
        $wp_admin_bar->add_menu( array(
            'parent' => 'wp-logo',
            'id'     => 'about',
            'title'  => __('About WordPress'),
            'href'  => self_admin_url( 'about.php' ),
        ) );
    }

    $wp_admin_bar->add_menu( array(
        'parent'    => 'wp-logo-external',
        'id'        => 'wporg',
        'title'     => '<img src="/favicon.ico"> ElastosOrg',
        'href'      => 'http://elastos.org/',
    ) );

/*
	$wp_admin_bar->add_menu( array(
		'id'    => 'wp-logo',
		'title' => '<span class="ab-icon"></span>',
		'href'  => self_admin_url( 'about.php' ),
		'meta'  => array(
			'title' => __('About WordPress'),
		),
	) );

	if ( is_user_logged_in() ) {
		// Add "About WordPress" link
		$wp_admin_bar->add_menu( array(
			'parent' => 'wp-logo',
			'id'     => 'about',
			'title'  => __('About WordPress'),
			'href'  => self_admin_url( 'about.php' ),
		) );
	}

	// Add WordPress.org link
	$wp_admin_bar->add_menu( array(
		'parent'    => 'wp-logo-external',
		'id'        => 'wporg',
		'title'     => __('WordPress.org'),
		'href'      => __('http://wordpress.org/'),
	) );

	// Add codex link
	$wp_admin_bar->add_menu( array(
		'parent'    => 'wp-logo-external',
		'id'        => 'documentation',
		'title'     => __('Documentation'),
		'href'      => __('http://codex.wordpress.org/'),
	) );

	// Add forums link
	$wp_admin_bar->add_menu( array(
		'parent'    => 'wp-logo-external',
		'id'        => 'support-forums',
		'title'     => __('Support Forums'),
		'href'      => __('http://wordpress.org/support/'),
	) );

	// Add feedback link
	$wp_admin_bar->add_menu( array(
		'parent'    => 'wp-logo-external',
		'id'        => 'feedback',
		'title'     => __('Feedback'),
		'href'      => __('http://wordpress.org/support/forum/requests-and-feedback'),
	) );
*/
}

/**
 * Add the "My Account" item.
 *
 * @since 3.3.0
 */
function wp_admin_bar_my_account_menu( $wp_admin_bar ) {
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	if ( ! $user_id )
		return;

	$avatar = get_avatar( $user_id, 16 );
	$howdy  = sprintf( __('Hi, %1$s'), $current_user->display_name );
	$class  = empty( $avatar ) ? '' : 'with-avatar';
	$user_domain = bp_core_get_user_domain($user_id);
	$profile_url  = get_edit_profile_url( $user_id );

	$wp_admin_bar->add_menu( array(
		'id'        => 'my-account',
		'parent'    => 'top-secondary',
		'title'     => $howdy . $avatar,
		'href'      => $user_domain,
		'meta'      => array(
			'class'     => $class,
			'title'     => __('My Home'),
		),
	) );

	$wp_admin_bar->add_group( array(
		'parent' => 'my-account',
		'id'     => 'user-actions',
	) );

	$user_info  = get_avatar( $user_id, 64 );
	$user_info .= '<img src="/elorg_common/img/buddypress.png" style="float:left;width:16px"><span class="display-name">' . "{$current_user->display_name}</span>";

	$user_info .= "<span class='username'>@{$current_user->user_nicename}</span>";

	$wp_admin_bar->add_menu( array(
		'parent' => 'user-actions',
		'id'     => 'user-info',
		'title'  => $user_info,
		'href'   => $user_domain,
		'meta'   => array(
			'tabindex' => -1,
		),
	) );
	$wp_admin_bar->add_menu( array(
		'parent' => 'user-actions',
		'id'     => 'edit-profile',
		'title'  => __( 'Edit My Profile' ),
		'href' => $profile_url,
	) );
	$wp_admin_bar->add_menu( array(
		'parent' => 'user-actions',
		'id'     => 'logout',
		'title'  => __( 'Log Out' ),
		'href'   => wp_logout_url(),
	) );
}

/**
 * Add the "Site Name" menu.
 *
 * @since 3.3.0
 */
function wp_admin_bar_site_menu( $wp_admin_bar ) {
	global $current_site;

	// Don't show for logged out users.
	if ( ! is_user_logged_in() )
		return;

	// Show only when the user is a member of this site, or they're a super admin.
	if ( ! is_user_member_of_blog() && ! is_super_admin() )
		return;

	if ( ! current_user_can_for_blog(get_current_blog_id(), 'edit_dashboard') )
		return;

	$blogname = get_bloginfo('name');

	if ( empty( $blogname ) )
		$blogname = preg_replace( '#^(https?://)?(www.)?#', '', get_home_url() );

	if ( is_network_admin() ) {
		$blogname = sprintf( __('Network Admin: %s'), esc_html( $current_site->site_name ) );
	} elseif ( is_user_admin() ) {
		$blogname = sprintf( __('Global Dashboard: %s'), esc_html( $current_site->site_name ) );
	}

	$title = wp_html_excerpt( $blogname, 40 );
	if ( $title != $blogname )
		$title = trim( $title ) . '&hellip;';

	$wp_admin_bar->add_menu( array(
		'id'    => 'site-name',
		'title' => $title,
		'href'  => is_admin() ? home_url( '/' ) : admin_url(),
	) );

	// Create submenu items.

	if ( is_admin() ) {
		// Add an option to visit the site.
		$wp_admin_bar->add_menu( array(
			'parent' => 'site-name',
			'id'     => 'view-site',
			'title'  => __( 'Visit Site' ),
			'href'   => home_url( '/' ),
		) );

		if ( is_blog_admin() && is_multisite() && current_user_can( 'manage_sites' ) ) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'site-name',
				'id'     => 'edit-site',
				'title'  => __( 'Edit Site' ),
				'href'   => network_admin_url( 'site-info.php?id=' . get_current_blog_id() ),
			) );
		}

	} else {
		// We're on the front end, link to the Dashboard.
		$wp_admin_bar->add_menu( array(
			'parent' => 'site-name',
			'id'     => 'dashboard',
			'title'  => __( 'Dashboard' ),
			'href'   => admin_url(),
		) );

		// Add the appearance submenu items.
		//wp_admin_bar_appearance_menu( $wp_admin_bar );
	}
}

/**
 * Add the "My Sites/[Site Name]" menu and all submenus.
 *
 * @since 3.1.0
 */
function wp_admin_bar_my_sites_menu( $wp_admin_bar ) {
	global $wpdb;

	//show SNS menu
	$blog_id = get_current_blog_id();
	if ( $blog_id > 1 ) {
		$group_id = get_groupblog_group_id($blog_id);
		if ( !$group_id ) {
			$users = get_users('blog_id=' . $blog_id . '&role=administrator');

			if (is_array($users) && (!empty($users)) ) {
				$wp_admin_bar->add_menu( array(
					'id'    => 'my-sns',
					'title' => '<img src="/elorg_common/img/buddypress.png">',
					'href'  => 'http://elastos.org/members/' . $users[0]->user_nicename,
			        'meta'  => array(
			            'title' => $users[0]->user_nicename,
			        )
				) );
			} else {
				$wp_admin_bar->add_menu( array(
					'id'    => 'my-sns',
					'title' => '<img src="/elorg_common/img/buddypress.png">',
					'href'  => 'http://elastos.org/members/' . get_the_author_meta('nicename', get_current_user_id()),
			        'meta'  => array(
			            'title' => get_the_author_meta('nicename', get_current_user_id())
			        )
				) );
			}
		} else {
			$group = groups_get_group( array( 'group_id' => $group_id ) );
			$wp_admin_bar->add_menu( array(
				'id'    => 'my-sns',
				'title' => '<img src="/elorg_common/img/buddypress.png">',
				'href'  => bp_get_group_permalink($group),
		        'meta'  => array(
		            'title' => esc_attr($group->name)
		        )
			) );
		}
	}

	// Don't show for logged out users or single site mode.
	if ( ! is_user_logged_in() || ! is_multisite() )
		return;

	// Show only when the user has at least one site, or they're not a super admin.
	if ( count( $wp_admin_bar->user->blogs ) < 1 || is_super_admin() )
		return;

	if ( is_super_admin() ) {
		$wp_admin_bar->add_menu( array(
			'id'    => 'my-sites',
			'title' => __( 'My Sites' ),
			'href'  => admin_url( 'my-sites.php' ),
		) );

		$wp_admin_bar->add_group( array(
			'parent' => 'my-sites',
			'id'     => 'my-sites-super-admin',
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'my-sites-super-admin',
			'id'     => 'network-admin',
			'title'  => __('Network Admin'),
			'href'   => network_admin_url(),
		) );

		$wp_admin_bar->add_menu( array(
			'parent' => 'network-admin',
			'id'     => 'network-admin-d',
			'title'  => __( 'Dashboard' ),
			'href'   => network_admin_url(),
		) );
		$wp_admin_bar->add_menu( array(
			'parent' => 'network-admin',
			'id'     => 'network-admin-s',
			'title'  => __( 'Sites' ),
			'href'   => network_admin_url( 'sites.php' ),
		) );
		$wp_admin_bar->add_menu( array(
			'parent' => 'network-admin',
			'id'     => 'network-admin-u',
			'title'  => __( 'Users' ),
			'href'   => network_admin_url( 'users.php' ),
		) );
		$wp_admin_bar->add_menu( array(
			'parent' => 'network-admin',
			'id'     => 'network-admin-v',
			'title'  => __( 'Visit Network' ),
			'href'   => network_home_url(),
		) );
	} else {
		$primary_blog = get_user_meta( get_current_user_id(), 'primary_blog', true );
		$blog_url = get_home_url($primary_blog);

		$wp_admin_bar->add_menu( array(
			'id'    => 'my-sites',
			'title' => __( 'My Sites' ),
			'href'  => rtrim($blog_url,'/') . '/wp-admin/my-sites.php',
		) );
	}

	// Add site links
	$wp_admin_bar->add_group( array(
		'parent' => 'my-sites',
		'id'     => 'my-sites-list',
		'meta'   => array(
			'class' => is_super_admin() ? 'ab-sub-secondary' : '',
		),
	) );

	$blue_wp_logo_url = includes_url('images/wpmini-blue.png');

	foreach ( (array) $wp_admin_bar->user->blogs as $blog ) {

	if ( current_user_can_for_blog($blog->userblog_id, 'edit_posts') ) {
		// @todo Replace with some favicon lookup.
		//$blavatar = '<img src="' . esc_url( blavatar_url( blavatar_domain( $blog->siteurl ), 'img', 16, $blue_wp_logo_url ) ) . '" alt="Blavatar" width="16" height="16" />';
		$blavatar = '<img src="' . esc_url($blue_wp_logo_url) . '" alt="' . esc_attr__( 'Blavatar' ) . '" width="16" height="16" class="blavatar"/>';

		$blogname = empty( $blog->blogname ) ? $blog->domain : $blog->blogname;
		$menu_id  = 'blog-' . $blog->userblog_id;

		$wp_admin_bar->add_menu( array(
			'parent'    => 'my-sites-list',
			'id'        => $menu_id,
			'title'     => $blavatar . $blogname,
			'href'      => get_home_url( $blog->userblog_id ),
		) );

		if ( current_user_can_for_blog($blog->userblog_id, 'edit_dashboard') ) {
			$wp_admin_bar->add_menu( array(
				'parent' => $menu_id,
				'id'     => $menu_id . '-d',
				'title'  => __( 'Dashboard' ),
				'href'   => get_admin_url( $blog->userblog_id ),
			) );
		}

		$wp_admin_bar->add_menu( array(
			'parent' => $menu_id,
			'id'     => $menu_id . '-n',
			'title'  => __( 'New Post' ),
			'href'   => get_admin_url( $blog->userblog_id, 'post-new.php' ),
		) );

		if ( current_user_can_for_blog($blog->userblog_id, 'moderate_comments') ) {
			$wp_admin_bar->add_menu( array(
				'parent' => $menu_id,
				'id'     => $menu_id . '-c',
				'title'  => __( 'Manage Comments' ),
				'href'   => get_admin_url( $blog->userblog_id, 'edit-comments.php' ),
			) );
		}

		$wp_admin_bar->add_menu( array(
			'parent' => $menu_id,
			'id'     => $menu_id . '-v',
			'title'  => __( 'Visit Site' ),
			'href'   => get_home_url( $blog->userblog_id, '/' ),
		) );
		}
	}
}

/**
 * Provide a shortlink.
 *
 * @since 3.1.0
 */
function wp_admin_bar_shortlink_menu( $wp_admin_bar ) {
	$short = wp_get_shortlink( 0, 'query' );
	$id = 'get-shortlink';

	if ( empty( $short ) )
		return;

	$html = '<input class="shortlink-input" type="text" readonly="readonly" value="' . esc_attr( $short ) . '" />';

	$wp_admin_bar->add_menu( array(
		'id' => $id,
		'title' => __( 'Shortlink' ),
		'href' => $short,
		'meta' => array( 'html' => $html ),
	) );
}

/**
 * Provide an edit link for posts and terms.
 *
 * @since 3.1.0
 */
function wp_admin_bar_edit_menu( $wp_admin_bar ) {
	global $post, $tag, $wp_the_query;

	if ( is_admin() ) {
		$current_screen = get_current_screen();

		if ( 'post' == $current_screen->base
			&& 'add' != $current_screen->action
			&& ( $post_type_object = get_post_type_object( $post->post_type ) )
			&& current_user_can( $post_type_object->cap->read_post, $post->ID )
			&& ( $post_type_object->public ) )
		{
			$wp_admin_bar->add_menu( array(
				'id' => 'view',
				'title' => $post_type_object->labels->view_item,
				'href' => get_permalink( $post->ID )
			) );
		} elseif ( 'edit-tags' == $current_screen->base
			&& isset( $tag ) && is_object( $tag )
			&& ( $tax = get_taxonomy( $tag->taxonomy ) )
			&& $tax->public )
		{
			$wp_admin_bar->add_menu( array(
				'id' => 'view',
				'title' => $tax->labels->view_item,
				'href' => get_term_link( $tag )
			) );
		}
	} else {
		$current_object = $wp_the_query->get_queried_object();

		if ( empty( $current_object ) )
			return;

		if ( ! empty( $current_object->post_type )
			&& ( $post_type_object = get_post_type_object( $current_object->post_type ) )
			&& current_user_can( $post_type_object->cap->edit_post, $current_object->ID )
			&& ( $post_type_object->show_ui || 'attachment' == $current_object->post_type ) )
		{
			$wp_admin_bar->add_menu( array(
				'id' => 'edit',
				'title' => $post_type_object->labels->edit_item,
				'href' => get_edit_post_link( $current_object->ID )
			) );
		} elseif ( ! empty( $current_object->taxonomy )
			&& ( $tax = get_taxonomy( $current_object->taxonomy ) )
			&& current_user_can( $tax->cap->edit_terms )
			&& $tax->show_ui )
		{
			$wp_admin_bar->add_menu( array(
				'id' => 'edit',
				'title' => $tax->labels->edit_item,
				'href' => get_edit_term_link( $current_object->term_id, $current_object->taxonomy )
			) );
		}
	}
}

/**
 * Add "Add New" menu.
 *
 * @since 3.1.0
 */
function wp_admin_bar_new_content_menu( $wp_admin_bar ) {
	$actions = array();

	$cpts = (array) get_post_types( array( 'show_in_admin_bar' => true ), 'objects' );

	if ( isset( $cpts['post'] ) && current_user_can( $cpts['post']->cap->edit_posts ) ) {
		$actions[ 'post-new.php' ] = array( $cpts['post']->labels->name_admin_bar, 'new-post' );
		unset( $cpts['post'] );
	}

	if ( current_user_can( 'upload_files' ) )
		$actions[ 'media-new.php' ] = array( _x( 'Media', 'add new from admin bar' ), 'new-media' );

	if ( current_user_can( 'manage_links' ) )
		$actions[ 'link-add.php' ] = array( _x( 'Link', 'add new from admin bar' ), 'new-link' );

	if ( isset( $cpts['page'] ) && current_user_can( $cpts['page']->cap->edit_posts ) ) {
		$actions[ 'post-new.php?post_type=page' ] = array( $cpts['page']->labels->name_admin_bar, 'new-page' );
		unset( $cpts['page'] );
	}

	// Add any additional custom post types.
	foreach ( $cpts as $cpt ) {
		if ( ! current_user_can( $cpt->cap->edit_posts ) )
			continue;

		$key = 'post-new.php?post_type=' . $cpt->name;
		$actions[ $key ] = array( $cpt->labels->name_admin_bar, 'new-' . $cpt->name );
	}

	if ( current_user_can( 'create_users' ) || current_user_can( 'promote_users' ) )
		$actions[ 'user-new.php' ] = array( _x( 'User', 'add new from admin bar' ), 'new-user' );

	if ( ! $actions )
		return;

	$title = '<span class="ab-icon"></span><span class="ab-label">' . _x( 'New', 'admin bar menu group label' ) . '</span>';

	$wp_admin_bar->add_menu( array(
		'id'    => 'new-content',
		'title' => $title,
		'href'  => admin_url( current( array_keys( $actions ) ) ),
		'meta'  => array(
			'title' => _x( 'Add New', 'admin bar menu group label' ),
		),
	) );

	foreach ( $actions as $link => $action ) {
		list( $title, $id ) = $action;

		$wp_admin_bar->add_menu( array(
			'parent'    => 'new-content',
			'id'        => $id,
			'title'     => $title,
			'href'      => admin_url( $link )
		) );
	}
}

/**
 * Add edit comments link with awaiting moderation count bubble.
 *
 * @since 3.1.0
 */
function wp_admin_bar_comments_menu( $wp_admin_bar ) {
	if ( !current_user_can('edit_posts') )
		return;

	$awaiting_mod = wp_count_comments();
	$awaiting_mod = $awaiting_mod->moderated;
	$awaiting_title = esc_attr( sprintf( _n( '%s comment awaiting moderation', '%s comments awaiting moderation', $awaiting_mod ), number_format_i18n( $awaiting_mod ) ) );

	$icon  = '<span class="ab-icon"></span>';
	$title = '<span id="ab-awaiting-mod" class="ab-label awaiting-mod pending-count count-' . $awaiting_mod . '">' . number_format_i18n( $awaiting_mod ) . '</span>';

	$wp_admin_bar->add_menu( array(
		'parent'    => 'top-secondary',
		'id'    => 'comments',
		'title' => $icon . $title,
		'href'  => admin_url('edit-comments.php'),
		'meta'  => array( 'title' => $awaiting_title ),
	) );
}

/**
 * Add appearance submenu items to the "Site Name" menu.
 *
 * @since 3.1.0
 */
function wp_admin_bar_appearance_menu( $wp_admin_bar ) {
	$wp_admin_bar->add_group( array( 'parent' => 'site-name', 'id' => 'appearance' ) );

	if ( current_user_can( 'switch_themes' ) || current_user_can( 'edit_theme_options' ) )
		$wp_admin_bar->add_menu( array( 'parent' => 'appearance', 'id' => 'themes', 'title' => __('Themes'), 'href' => admin_url('themes.php') ) );

	if ( ! current_user_can( 'edit_theme_options' ) )
		return;

	$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$wp_admin_bar->add_menu( array(
		'parent' => 'appearance',
		'id'     => 'customize',
		'title'  => __('Customize'),
		'href'   => add_query_arg( 'url', urlencode( $current_url ), wp_customize_url() ),
		'meta'   => array(
			'class' => 'hide-if-no-customize',
		),
	) );
	add_action( 'wp_before_admin_bar_render', 'wp_customize_support_script' );

	if ( current_theme_supports( 'widgets' )  )
		$wp_admin_bar->add_menu( array( 'parent' => 'appearance', 'id' => 'widgets', 'title' => __('Widgets'), 'href' => admin_url('widgets.php') ) );

	 if ( current_theme_supports( 'menus' ) || current_theme_supports( 'widgets' ) )
		$wp_admin_bar->add_menu( array( 'parent' => 'appearance', 'id' => 'menus', 'title' => __('Menus'), 'href' => admin_url('nav-menus.php') ) );

	if ( current_theme_supports( 'custom-background' ) )
		$wp_admin_bar->add_menu( array( 'parent' => 'appearance', 'id' => 'background', 'title' => __('Background'), 'href' => admin_url('themes.php?page=custom-background') ) );

	if ( current_theme_supports( 'custom-header' ) )
		$wp_admin_bar->add_menu( array( 'parent' => 'appearance', 'id' => 'header', 'title' => __('Header'), 'href' => admin_url('themes.php?page=custom-header') ) );
}

/**
 * Provide an update link if theme/plugin/core updates are available.
 *
 * @since 3.1.0
 */
function wp_admin_bar_updates_menu( $wp_admin_bar ) {

	$update_data = wp_get_update_data();

	if ( !$update_data['counts']['total'] )
		return;

	$title = '<span class="ab-icon"></span><span class="ab-label">' . number_format_i18n( $update_data['counts']['total'] ) . '</span>';

	$wp_admin_bar->add_menu( array(
		'id'    => 'updates',
		'title' => $title,
		'href'  => network_admin_url( 'update-core.php' ),
		'meta'  => array(
			'title' => $update_data['title'],
		),
	) );
}

/**
 * Add search form.
 *
 * @since 3.3.0
 */
function wp_admin_bar_search_menu( $wp_admin_bar ) {
	if ( is_admin() )
		return;

$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4);
//whether the request come from Chinese
if (preg_match("/zh-c/i", $lang)) {
    $form = '<div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare" style="max-height:12px;">';
    $form .= '  <span class="bds_more">....</span>';
    $form .= '  </div>';
    $form .= '  <script type="text/javascript" id="bdshare_js" data="type=tools&amp;mini=1&amp;uid=5906275" ></script>';
    $form .= '  <script type="text/javascript" id="bdshell_js"></script>';
    $form .= '  <script type="text/javascript">';
    $form .= '  document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)';
    $form .= '  </script>';

	$wp_admin_bar->add_menu( array(
		'parent' => 'top-secondary',
		'id'     => 'bdshare',
		'title'  => $form,
		'meta'   => array(
			'class'    => 'admin-bar-search',
			'tabindex' => -1,
		)
	) );
}

	$form  = '<form action="' . esc_url( home_url( '/' ) ) . '" method="get" id="adminbarsearch">';
	$form .= '<input class="adminbar-input" name="solr" id="adminbar-search" tabindex="10" type="text" value="" maxlength="150" />';
	$form .= '<input type="submit" class="adminbar-button" value="' . __('Search') . '"/>';
	$form .= '</form>';

	$wp_admin_bar->add_menu( array(
		'parent' => 'top-secondary',
		'id'     => 'search',
		'title'  => $form,
		'meta'   => array(
			'class'    => 'admin-bar-search',
			'tabindex' => -1,
		)
	) );
}

/**
 * Add secondary menus.
 *
 * @since 3.3.0
 */
function wp_admin_bar_add_secondary_groups( $wp_admin_bar ) {
	$wp_admin_bar->add_group( array(
		'id'     => 'top-secondary',
		'meta'   => array(
			'class' => 'ab-top-secondary',
		),
	) );

	$wp_admin_bar->add_group( array(
		'parent' => 'wp-logo',
		'id'     => 'wp-logo-external',
		'meta'   => array(
			'class' => 'ab-sub-secondary',
		),
	) );
}

/**
 * Style and scripts for the admin bar.
 *
 * @since 3.1.0
 *
 */
function wp_admin_bar_header() { ?>
<style type="text/css" media="print">#wpadminbar { display:none; }</style>
<?php
}

/**
 * Default admin bar callback.
 *
 * @since 3.1.0
 *
 */
function _admin_bar_bump_cb() { ?>
<style type="text/css" media="screen">
	html { margin-top: 28px !important; }
	* html body { margin-top: 28px !important; }
</style>
<?php
}

/**
 * Set the display status of the admin bar.
 *
 * This can be called immediately upon plugin load. It does not need to be called from a function hooked to the init action.
 *
 * @since 3.1.0
 *
 * @param bool $show Whether to allow the admin bar to show.
 * @return void
 */
function show_admin_bar( $show ) {
	global $show_admin_bar;
	$show_admin_bar = (bool) $show;
}

/**
 * Determine whether the admin bar should be showing.
 *
 * @since 3.1.0
 *
 * @return bool Whether the admin bar should be showing.
 */
function is_admin_bar_showing() {
	global $show_admin_bar, $pagenow;

	// For all these types of requests, we never want an admin bar.
	if ( defined('XMLRPC_REQUEST') || defined('APP_REQUEST') || defined('DOING_AJAX') || defined('IFRAME_REQUEST') )
		return false;

	// Integrated into the admin.
	if ( is_admin() )
		return true;

	if ( ! isset( $show_admin_bar ) ) {
		if ( ! is_user_logged_in() || 'wp-login.php' == $pagenow ) {
			$show_admin_bar = false;
		} else {
			$show_admin_bar = _get_admin_bar_pref();
		}
	}

	$show_admin_bar = apply_filters( 'show_admin_bar', $show_admin_bar );

	return $show_admin_bar;
}

/**
 * Retrieve the admin bar display preference of a user.
 *
 * @since 3.1.0
 * @access private
 *
 * @param string $context Context of this preference check. Defaults to 'front'. The 'admin'
 * 	preference is no longer used.
 * @param int $user Optional. ID of the user to check, defaults to 0 for current user.
 * @return bool Whether the admin bar should be showing for this user.
 */
function _get_admin_bar_pref( $context = 'front', $user = 0 ) {

	/*
	 * elastos.org display admin-bar always
	 */
	return  true;

	/*
	$pref = get_user_option( "show_admin_bar_{$context}", $user );
	if ( false === $pref )
		return true;

	return 'true' === $pref;
	*/
}
