<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp1' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         '()y>iev4n)l6E/?*T60yfBXVbZf&ws[]ua:ml=88Kind@@OmQhgKTb^Kc&?&R=%s' );
define( 'SECURE_AUTH_KEY',  'j|MkWHZ?O2WQtGd!Zx;M0 (Zn81dA3]2KOIF0J]Z(4K{vBVSgq.}y]j0x)(4j;OH' );
define( 'LOGGED_IN_KEY',    'ZVePjLrQa8*3X9kv)/#a.JyG_oSPPna?lA?uGAwU@fL;-AY97N9m[Q@:LVgT2u;m' );
define( 'NONCE_KEY',        '{_:j$(reaE/{w~*3Vuq v#h{Q=1Q<QU3q[LLFB`hy^AQ17p0ZQH3>7)`F!7&O(SK' );
define( 'AUTH_SALT',        ']m9J{1D_)R0D+iA;mtg+*3w{M4aJC>$[-SGnMBaY&qEIZTlw>Y)9QRUCm3Mcb9,^' );
define( 'SECURE_AUTH_SALT', 'cxw?}jSjd=GEx__&z}(fT.Ev$DHQpS>[a?;yu_tn7$s99JXVFo4cR_P`%*o:E >t' );
define( 'LOGGED_IN_SALT',   'W9`OFMCuAfi0@CPc&Yw_M.ak;=n(3FpJLkj)@TiBU!xX%qm3k0,(a27xxmM0{x7V' );
define( 'NONCE_SALT',       'pfo%l>>UX _rpV7O5c]x+~6uYyHL$_Kttdt;=Xf.!YuimRzuH!HHOH8*KL E^0^0' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
// define( 'WP_DEBUG', false );
define( 'WP_DEBUG', true );
/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
