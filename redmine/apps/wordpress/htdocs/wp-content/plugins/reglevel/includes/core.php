<?php
require_once(RL_BASE_INC . '/functions.php');

/**
 * The following code creates our admin menu and lets users specify options
 *
 */
add_action('admin_menu', 'rl_add_pages');
function rl_add_pages() {
    add_submenu_page('users.php','RegLevel Settings', 'RegLevel', 8, RL_BASE_INC . '/admin.php');
}

/**  
  *  The following sets of functions are the 'new' core to the RegLevel plugin.
  */

define('REGLEVEL_REWRITERULES', '1');								// flag to determine if plugin can change WP rewrite rules
define('REGLEVEL_QUERYVAR', 'reglevel');							// get/post variable name for querying tag/keyword from WP
define('REGLEVEL_META', 'regpage');									// post meta key used in the wp database
define('REGLEVEL_TEMPLATE', '');									// template file to use for registration pages - should be NULL

function reglevel_init() {
    global $wp_rewrite;
    
    /* Shouldn't need to change this - can set to 0 if you want to force permalinks off */
    if (isset($wp_rewrite) && $wp_rewrite->using_permalinks()) {
        define('REGLEVEL_REWRITEON', '1');							// turn on nice permalinks
        define('REGLEVEL_LINKBASE', $wp_rewrite->root);				// set to "index.php/" if using that style
    } else {
        define('REGLEVEL_REWRITEON', '0');							// old school links
        define('REGLEVEL_LINKBASE', '');							// don't need this
    }

    /* generate rewrite rules for above queries */
    if (REGLEVEL_REWRITEON && REGLEVEL_REWRITERULES)
        add_filter('search_rewrite_rules', 'reglevel_createRewriteRules');
}
add_action('init','reglevel_init');

function reglevel_createRewriteRules($rewrite) {
	global $wp_rewrite;
	
	// add rewrite tokens
	$keytag_token = '%' . REGLEVEL_QUERYVAR . '%';
	$wp_rewrite->add_rewrite_tag($keytag_token, '(.+)', REGLEVEL_QUERYVAR . '=');
    
	$reglevel_structure = $wp_rewrite->root . REGLEVEL_QUERYVAR . "/$keytag_token";
	$reglevel_rewrite = $wp_rewrite->generate_rewrite_rules($reglevel_structure);
	
	return ( $rewrite + $reglevel_rewrite );
}

function is_regpage() {
    global $wp_version;
    $regpage = ( isset($wp_version) && ($wp_version >= 2.0) ) ? 
                get_query_var(REGLEVEL_QUERYVAR) : 
                $GLOBALS[REGLEVEL_QUERYVAR];
	if (!is_null($regpage) && ($regpage != ''))
		return true;
	else
		return false;
}

add_filter('query_vars', 'reglevel_addQueryVar');
add_action('parse_query', 'reglevel_parseQuery');

function reglevel_addQueryVar($wpvar_array) {
	$wpvar_array[] = REGLEVEL_QUERYVAR;
	return($wpvar_array);
}

function reglevel_parseQuery() {
	// if this is a keyword query, then reset other is_x flags and add query filters
	if (is_regpage()) {
		global $wp_query;
		$wp_query->is_single = false;
		$wp_query->is_page = false;
		$wp_query->is_archive = false;
		$wp_query->is_search = false;
		$wp_query->is_home = false;
		
//		add_filter('posts_where', 'reglevel_postsWhere');
//		add_filter('posts_join', 'reglevel_postsJoin');
		add_action('template_redirect', 'reglevel_redirect');
	}
}

function reglevel_postsWhere($where) {
    global $wp_version;
    $regpage = ( isset($wp_version) && ($wp_version >= 2.0) ) ? 
                get_query_var(REGLEVEL_QUERYVAR) : 
                $GLOBALS[REGLEVEL_QUERYVAR];

    $where .= " AND wp_meta.meta_key = '" . REGLEVEL_META . "' ";
	$where .= " AND wp_meta.meta_value LIKE '%" . $regpage . "%' ";

    $where = str_replace(' AND (post_status = "publish"', ' AND ((post_status = \'static\' OR post_status = \'publish\')', $where);
    
	return ($where);
}

function reglevel_postsJoin($join) {
	global $wpdb;
	$join .= " LEFT JOIN $wpdb->postmeta AS jreglevel_meta ON ($wpdb->posts.ID = jreglevel_meta.post_id) ";
	return ($join);
}

function reglevel_redirect() {
	if (is_regpage()) {
		reglevel_start_session();		
		require('./wp-load.php');
		wp_redirect('wp-login.php?action=register');
	}
	return;
}

function reglevel_start_session() {
	global $wpdb;
	$regpage = get_query_var(REGLEVEL_QUERYVAR);
	$rlquery = "SELECT regleveldef FROM " . $wpdb->prefix . "reglevel WHERE pagedef = '" . $regpage . "'";
	$rlevel = $wpdb->get_var($rlquery);
	setcookie('reglevel',$rlevel,time()+600);	
}

function reglevel_promote($user_ID) {
	if(isset($_COOKIE['reglevel'])) {
		$reglevel_level = $_COOKIE['reglevel'];
		$update = 'promote';
		$role=$reglevel_level;
		$user = new WP_User($user_ID);
		$user->set_role($reglevel_level);
	}
	setcookie('reglevel','',time()-600);
	return $user_ID;	
}

add_action ( 'user_register', 'reglevel_promote' );
?>