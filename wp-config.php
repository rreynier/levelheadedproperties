<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'scotchbox');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'mf`?F*M6!#J]X5-Fo6|>3kk-gZfu?KWzTnzr+[nXGnE0$+zcA?w4#z7R6ccj03`t');
define('SECURE_AUTH_KEY',  'IEqrdp!f3#j;,#t-)us6!j*dV1==jPfGooi_O}Dok_#p7l)pkc+hFK~B76XE?!>3');
define('LOGGED_IN_KEY',    'E{L@K|0fNshA*1.K7jTK#CgYb|mEo>T:+kJ>=%m-0f`r/.O}GEBLLT5mH/yX$TSJ');
define('NONCE_KEY',        'uSgL+E_5<O7W-d,=|GE<#84bZF,P9;!S]<, Crxz_JktOT?kzNx4juD`,$6N-3JI');
define('AUTH_SALT',        '/OL)mNY9-nMl=3iFI%9@~SZ{?bm&w4%&P;vIhRP}9Ml1+6>@k:Uy|_QYB+Tcj4-8');
define('SECURE_AUTH_SALT', '9J+W,3cDEvhm`,=pD{[a@MCeF+tfZRG3~iEt>ZoebI|O~*z[D%5.==R-~|5duK^m');
define('LOGGED_IN_SALT',   '1mMl=.Frd 6R~6e+au+T(G$M>PhW2v?XF(tbp],e>~jMxZ1#`+|=s=IHVeN?-E6b');
define('NONCE_SALT',       ',*NmIpO[>R %$ba;[~#9,Q^)=/3A<27twEr~E]|{#KnVK6r;H8G5J}(uEs6QsdDo');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
