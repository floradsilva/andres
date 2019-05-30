<?php
define( 'WP_CACHE', true ); // Added by WP Rocket
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'upfga_ei1_1' );

/** MySQL database username */
define( 'DB_USER', 'wordpressuser' );

/** MySQL database password */
define( 'DB_PASSWORD', 'wisdmlabs' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY', 'ux:-X.$=&n$ S/#qPws?}l3sDolv2a9,O))hnVOXK^Lmqp],kj|HCn=_+Q&ty2*-' );
define( 'SECURE_AUTH_KEY', 'J/ysb],,_]1mV%0d/Azu--d]iMGLs8|xVPMO~ikObjIa7/ER;#-sJYagO^QiL-eU' );
define( 'LOGGED_IN_KEY', '1@qX%!*ohq5r7J;LYiF;a%L)u|Coz-i=yP6vX? e|n>WwW@Y_@nhZ:eyD.~64}<?' );
define( 'NONCE_KEY', 'a;!`dX/s5Rf$b&I+?WHM6<`f3^Zl15I+|,7BxfSdV>1,M8-p|vc>.~6`zN-@h,-C' );
define( 'AUTH_SALT', 'XQ_y3 ry2R31U{_JYP5@8vSj 5@ TJ[mb^A7j]vpC-yfYB^5uoh1VsvU]0bCkRH+' );
define( 'SECURE_AUTH_SALT', 'Iy&0H2{:c+uU@%he}Zz3N-%AQ{y8of!l+.LYkJ-f|1JNXBV-gnlD.]FB9g{HO+Hh' );
define( 'LOGGED_IN_SALT', '~ 1lx{+n#P+~q}vIb**vw9+lFj+Ki}ISC(k^|Vxb,-|;7L1g--1Jo;$[4Y/-h/5~' );
define( 'NONCE_SALT', 'xF%!^kv]M6;Db|0WF~B=3$p1$$)nkR7Ox=GzbFh^_7YQ-z.1qW8j%+znj=3Uj JC' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'upfss_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
