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
define('DB_NAME', 'jlum');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'u)FbYKO8n3S nGMHYg~u.k~A/QSW)3gUQYkpje7m`q:7DAy8k5^yC_V~(cHyu2iW');
define('SECURE_AUTH_KEY',  ']22>&UD1t]y`quvHuPCIkU;t!MR2s8*[H-Ptz^MdxjX|Y2|#@|3$;MWK`#jqR4!u');
define('LOGGED_IN_KEY',    '[QM6B.w:}-bzsPRd[.|Th9#:hTOLaUfT{!}+0AOGg`x{HV|k%(fV2};x17.7o A3');
define('NONCE_KEY',        '>jG.^T~IJwbrFhXRu}]JpzojoT.fB|]^d3 $>S:}/4&% d:E[3V5DxoXTS/s@;sW');
define('AUTH_SALT',        'D}UlWkN?bdxPe+bOa~3LuIJma|EJjRyIg[+Eq:cT+{2 sFKpjwe}0LT(oHja()^:');
define('SECURE_AUTH_SALT', '0sUdyc2(Gxc+RCmrt:[MeTZ.dB+QO_L3nHru!%.Z]W<(5X4dGGcUsIEz]Zc4s]ds');
define('LOGGED_IN_SALT',   'CpYZ;?]1).~OHl*}VtF={OnUq9J%!.d3=<J$Hty+.>%J%:+<oDDvSxy``$@ka?CJ');
define('NONCE_SALT',       'bes):EkFPCAF_++_[|eJht6^[(.SJ1oIw|=-BgICq[D_xQjZOC0/X_fU.z,yl@{}');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);



/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');