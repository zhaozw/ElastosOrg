<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
define('WP_ALLOW_MULTISITE',true);
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'kortide');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');
/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define('WP_MEMORY_LIMIT','1024M');
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'm[^m;OvYX2#Th1)sl}KP,K@)~&=AHXZ]ZJ6.nNjroC+Qjw PBe#Fn$9Bpl}LcF>5');
define('SECURE_AUTH_KEY',  '<:*Px0! i&0%jF) itU%xO0tkPTv4B4)7zxm$&IL1F_A$=lUx@Fn&wOYxmQB+Z[7');
define('LOGGED_IN_KEY',    'd%y@+<o)QJHY3HPSX`oyjN[=uqS&2c=D|ur QVrW>l#O&edy V)ZTH:vG/7@GnwZ');
define('NONCE_KEY',        ']64p?un>#:zSMR+h.8%RR}SGcbB+D*KmFb,e6f@P$`eE5O$o*`yr9{aN3uKb{V |');
define('AUTH_SALT',        '38X]m%Mm-<<rMNs^v)aoXf>]&.*HGMg93Csp8FBb]JlwG[_wF;:Na}0U`1%|p+N7');
define('SECURE_AUTH_SALT', '2#q4L>m?#lGN+&U*?;rs;G2ryE4(D3J4NBx*4zNSnfiP+yY*os`g~T$FLK$EY-2>');
define('LOGGED_IN_SALT',   '!bQ#.DPnl+h1`/</H%.pH/N]0]vh#[<9ZrpGcRs<ss?(~|c1ACj^4e[8>g#*7{c9');
define('NONCE_SALT',       'cUt~`N,o:&Q~(m9@*bE:6zZ`E0gf )>Q[pMg82=Op_f(H6lBpYE@+[yFE1B*I8eY');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', true );
$base = '/';
define( 'DOMAIN_CURRENT_SITE', 'elastos.org' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );

define( 'COOKIEHASH', 'd13577c3b31c835f8930e733eb2d5def' );
define( 'COOKIE_DOMAIN', '.elastos.org' );
define( 'SITECOOKIEPATH', '/' );
define( 'COOKIEPATH', '/' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
