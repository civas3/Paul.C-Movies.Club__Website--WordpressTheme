<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'movies_club' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '#qxNlvCGdB=<E$XQ(90kIDCSxD-fAfh>C2N0g[dKz>AC: 5edqVw+|<%gI{{$Tk0' );
define( 'SECURE_AUTH_KEY',  'lD%gMU~}]_+gNd>0Y^9e}>(oQB]Zqsdr/F&oqXIqY;!S_PERN`y;.>KrAto=HJIr' );
define( 'LOGGED_IN_KEY',    'N1UjdR(,dj Y$@N#3Evh>K*}uHN+NsqXkZd&9OxCB_rz[(1[M8uw520uJW/} tEx' );
define( 'NONCE_KEY',        'M8f$7SX^R9jU(?-5C8>sT#q#i@2>n}4h?%gJVy}2ZW!%^haFz3u2T ]U93Qxt(UT' );
define( 'AUTH_SALT',        'p11cevfoe|c=P#NNz:x>Z}NZr;{7yYfcW w[db9d-@z`$h=[$>*N=F:o.-JpSG&4' );
define( 'SECURE_AUTH_SALT', ']keI|x41XvvYS,HR1EVf2oy1UMF!-mH{9]Q-* ~)]WpA{0@X:/x~h2)*GfZG#0:-' );
define( 'LOGGED_IN_SALT',   'ubG *g-fi#z[.Bt/gQee2mWs~)1Z%Drv-8pKuG:/a{L>PYhz11|73Bx^&>>Om0i*' );
define( 'NONCE_SALT',       '&]kiU8?W<67hs fFO?6,SXZ_|Lv<]PwXc;N]Mou:Cw13Or+!,?2OQxVR-wZJpQKm' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
