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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'binno' );

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
define( 'AUTH_KEY',         '@lgp/3+f=K2B)x<;#WY4*3X^h+>_5phSJRT-w]tO`A)U.-5Ugq@xRL;t3YAOT%99' );
define( 'SECURE_AUTH_KEY',  '{o<)&>a,/;E#Nf/Y_/)~LYOB,yZgryF#27pL8/tBMwGXJ~t+cV=1DvRNHOvsOl7_' );
define( 'LOGGED_IN_KEY',    'EGq*0t4iuYqc^D1untgA3) 6mmyXK?1c4!%HPLFmc10U<ZrB9[j14S<O.ZD,b D(' );
define( 'NONCE_KEY',        '_L%;5yO<yj>AZ6682LOJVor^U3{-a8)l?gCe*P wb)*]/IN3jJ,A=qt}k/bGQR%r' );
define( 'AUTH_SALT',        ',Yn{_J@4fB$t0YVk^~4N.A8D #Ek Ed[qx*PjY;{jI?B-R]Dj~vd63QC%oV2qWl1' );
define( 'SECURE_AUTH_SALT', '<kK?aNi]02Z9>[^Hj$?~&9SNhj}`n,Cmit#9_w]yo_blJH) ~hr 2QJf|cv3o#;$' );
define( 'LOGGED_IN_SALT',   'CmTY!Z.gjndV=s,*?d)k&$M>.]&ljv[DH_C9i}`Qi~/O.S ^`hUwU)J82?.3pWi[' );
define( 'NONCE_SALT',       '+p,])=qljmhg+F.sK;s:(ODm5Rbuo2swih0uOw9HbH3o:FJsuF)G ~K7K}Z.{(($' );

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

set_time_limit(300);
