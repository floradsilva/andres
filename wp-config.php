<?php
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
define( 'DB_NAME', 'andres_woo' );

/** MySQL database username */
define( 'DB_USER', 'andres' );

/** MySQL database password */
define( 'DB_PASSWORD', '9GdCDR^f_hBgg?MH' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'F}8BMoGn)S(J)hXUCJ[_$`?k`.|N# %MxM()@[?KHZwvwHhtn<@f(R;B*>6spe/l' );
define( 'SECURE_AUTH_KEY',  'B}i$.!1l29j&gc:199NsF/x+N#}]s6{oBPichpmX4?>:]K%B,!b*i2I8a.fPQtZE' );
define( 'LOGGED_IN_KEY',    '9C0(lY~Y$i;5mKc@|B!?;z3ts8C(W. zu([s8^2PIce<MQ<6j.z-bEv),*2s^FX7' );
define( 'NONCE_KEY',        'PPZdMF0xXa4%wnU 8A6&QfYX+>4u7!?Et{AXrXIZF/2~7|%)TpRA7(P!*Sun;Q{T' );
define( 'AUTH_SALT',        'vbzWd`2/x,j&g5A/fX:*Oj#0N.l_KE{K=&]]{+`[2Ux[PAa`8}!W3YCnTD8$-Tf!' );
define( 'SECURE_AUTH_SALT', 'C_$7w~F1OyfNGQr==os,(SWF<C4jy%Zucj@KuRnX7#d0h-McE&d.uV<tB[K3nOpF' );
define( 'LOGGED_IN_SALT',   'd#HpnVgE1669+5zm9t{!ACT%y%OdbFJ9Jw/on%F&2fDvRl~=o5[<iSgF5/]c50Z4' );
define( 'NONCE_SALT',       '?pq90MMjQaxD@|Vx!H5q`O<*tGa3N(ytvZ;S2wce-,&%k^Xz07FBn&zMv/kOsP4i' );

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
