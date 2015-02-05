<?php

if ( !defined( 'COOKIEHASH' ) ) {
		$siteurl = 'http://elastos.org';
		if ( $siteurl )
			define( 'COOKIEHASH', md5( $siteurl ) );
		else
			define( 'COOKIEHASH', '' );	
	}

if ( !defined('LOGGED_IN_COOKIE') )
define('LOGGED_IN_COOKIE', 'wordpress_logged_in_' . COOKIEHASH);
//   wordpress/wp-config.php#46,#50
define('LOGGED_IN_KEY',    'Z1:_}m^gc3l;lMTVG35TF!1L}dEa-Vvow]i[[,;u9o1-305+zq6UPfk7hT[>Pif-');
define('LOGGED_IN_SALT',   '0sk=-T+5T{CO.&i^,-V%`A `=a+w 4-yV@{J>0f&+Y:W@I{(&7!S0eoQ-E/*?}#_');

function wp_validate_auth_cookie($cookie = '', $scheme = '') {
	
	if ( ! $cookie_elements = wp_parse_auth_cookie($cookie, $scheme) ) {
		return false;
	}
	extract($cookie_elements, EXTR_OVERWRITE);
	$expired = $expiration;
	if ( $expired < time() ) {
		return false;
	}
	$user = $username;
	if ( ! $user ) {
		return false;
	}

	$myconn = mysql_connect("localhost","root", "zhu") or die("Could not connect: " . mysql_error());
	mysql_select_db("wordpress", $myconn) or die("Could not select database");
	$strSql = 'select user_pass from wp_users where user_login = "' . $username . '"';
	$result = mysql_query($strSql,$myconn) or die("Query failed : " . mysql_error());
	$num = mysql_fetch_row($result);
	mysql_free_result($result);
	mysql_close($myconn);

	$user_pass = $num[0];
	$pass_frag = substr($user_pass, 8, 4);
	$key = wp_hash($username . $pass_frag . '|' . $expiration, $scheme);
	$hash = hash_hmac('md5', $username . '|' . $expiration, $key);

	if ( $hmac != $hash ) {
		return false;
	}

	return $user;
}

function wp_parse_auth_cookie($cookie = '', $scheme = '') {

	if ( empty($cookie) ) {
		$cookie_name = LOGGED_IN_COOKIE;
		$scheme = 'logged_in';	

		if ( empty($_COOKIE[$cookie_name]) )
			return false;
		$cookie = $_COOKIE[$cookie_name];
	}

	$cookie_elements = explode('|', $cookie);
	if ( count($cookie_elements) != 3 )
		return false;

	list($username, $expiration, $hmac) = $cookie_elements;

	return compact('username', 'expiration', 'hmac', 'scheme');
}


function wp_salt( $scheme = '' ) {
	static $cached_salts = array();
	
	if ( isset( $cached_salts[ $scheme ] ) ){
		return $cached_salts[ $scheme ];
	}

	static $duplicated_keys;
	$key = $salt = '';

	if ( in_array( $scheme, array( 'auth', 'secure_auth', 'logged_in', 'nonce' ) ) ) {
		foreach ( array( 'key', 'salt' ) as $type ) {
			$const = strtoupper( "{$scheme}_{$type}" );
			if ( defined( $const ) && constant( $const ) && empty( $duplicated_keys[ constant( $const ) ] ) ) {
				$$type = constant( $const );
			} 
		}
	} else {
		$salt = hash_hmac( 'md5', $scheme);
	}

	$cached_salts[ $scheme ] = $key . $salt;
	return $cached_salts[ $scheme ];
}

function wp_hash($data, $scheme = 'auth') {
	$salt = wp_salt($scheme);
	return hash_hmac('md5', $data, $salt);
}

