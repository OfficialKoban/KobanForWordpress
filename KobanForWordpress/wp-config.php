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
define('DB_NAME', 'kobancrmjxtest');

/** MySQL database username */
define('DB_USER', 'kobancrmjxtest');

/** MySQL database password */
define('DB_PASSWORD', 'Test50Kb');

/** MySQL hostname */
define('DB_HOST', 'kobancrmjxtest.mysql.db:3306');

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
define('AUTH_KEY',         '3hcJ5jPbrUMsySKrzuk9QVi79ndUA0UJRCOzxsivPm/ji0zXVct0OYz4t2BC');
define('SECURE_AUTH_KEY',  'TxTt4qDvHikBEFHNAv5FIpf9qVx0Rl9+u3Aq6pTkoU9c6PC7lZBM7FJ70Y1R');
define('LOGGED_IN_KEY',    'tptNkn4V/M734EOAk0hXQbTCflf0S0Wd9F+5HRViBtM+i1FvcyM6Vx8M26WF');
define('NONCE_KEY',        'KJVmO2quVS8KCrg6SO3tdH2tI7rCgpG4seg+SvEGS6jkxarapY2fAK5/mYin');
define('AUTH_SALT',        '6x4q718zP1EkNtztR1Qe2UjizXCUOpGq55L9nRBWJZyRaLlStWMvG01SJrqQ');
define('SECURE_AUTH_SALT', 'd9wSy628pMHiJWbbSy8XinDBOo4VOOb6p+h5erUXVGzONpwidZqWr+4tWl7f');
define('LOGGED_IN_SALT',   'CSEraSPmMUIMb6dSSEjgAis7JHckerKl6Bmj+jQXcppv/CGpZ0J+rZacQ334');
define('NONCE_SALT',       'fXzvmnnTR9Vcseg+YR6GdjUugoRysNxB8olDajT28Xw5mQHg6jHcxOzd60kx');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wor760_';

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
