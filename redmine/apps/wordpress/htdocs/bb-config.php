<?php
/** 
 * The base configurations of bbPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys and bbPress Language. You can get the MySQL settings from your
 * web host.
 *
 * This file is used by the installer during installation.
 *
 * @package bbPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for bbPress */
define( 'BBDB_NAME', 'wordpress' );

/** MySQL database username */
define( 'BBDB_USER', 'root' );

/** MySQL database password */
define( 'BBDB_PASSWORD', 'kortide' );

/** MySQL hostname */
define( 'BBDB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'BBDB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'BBDB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/bbpress/ WordPress.org secret-key service}
 *
 * @since 1.0
 */
define( 'BB_AUTH_KEY', 'm[^m;OvYX2#Th1)sl}KP,K@)~&=AHXZ]ZJ6.nNjroC+Qjw PBe#Fn$9Bpl}LcF>5' );
define( 'BB_SECURE_AUTH_KEY', '<:*Px0! i&0%jF) itU%xO0tkPTv4B4)7zxm$&IL1F_A$=lUx@Fn&wOYxmQB+Z[7' );
define( 'BB_LOGGED_IN_KEY', 'd%y@+<o)QJHY3HPSX`oyjN[=uqS&2c=D|ur QVrW>l#O&edy V)ZTH:vG/7@GnwZ' );
define( 'BB_NONCE_KEY', ']64p?un>#:zSMR+h.8%RR}SGcbB+D*KmFb,e6f@P$`eE5O$o*`yr9{aN3uKb{V |' );
/**#@-*/

/**
 * bbPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$bb_table_prefix = 'wp_bb_';

/**
 * bbPress Localized Language, defaults to English.
 *
 * Change this to localize bbPress. A corresponding MO file for the chosen
 * language must be installed to a directory called "my-languages" in the root
 * directory of bbPress. For example, install de.mo to "my-languages" and set
 * BB_LANG to 'de' to enable German language support.
 */
define( 'BB_LANG', 'en_US' );
$bb->custom_user_table = 'wp_users';
$bb->custom_user_meta_table = 'wp_usermeta';

$bb->uri = 'http://elastos.org/wp-content/plugins/buddypress//bp-forums/bbpress/';
$bb->name = 'Elastos Developer Forums';
$bb->wordpress_mu_primary_blog_id = 1;

define('BB_AUTH_SALT', '38X]m%Mm-<<rMNs^v)aoXf>]&.*HGMg93Csp8FBb]JlwG[_wF;:Na}0U`1%|p+N7');
define('BB_LOGGED_IN_SALT', '!bQ#.DPnl+h1`/</H%.pH/N]0]vh#[<9ZrpGcRs<ss?(~|c1ACj^4e[8>g#*7{c9');
define('BB_SECURE_AUTH_SALT', '2#q4L>m?#lGN+&U*?;rs;G2ryE4(D3J4NBx*4zNSnfiP+yY*os`g~T$FLK$EY-2>');

define('WP_AUTH_COOKIE_VERSION', 2);

?>