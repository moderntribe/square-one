<?php
/**
 * Modern Tribe Skeleton configuration
 * Based on Mark Jaquith's Skeleton repository
 * @link https://github.com/markjaquith/WordPress-Skeleton
 */


if ( ! empty( $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] ) && $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] === 'https' ) {
	$_SERVER[ 'HTTPS' ] = 'on';
}

if ( file_exists( dirname( __FILE__ ) . '/build-process.php' ) ) {
	include( dirname( __FILE__ ) . '/build-process.php' );
}

if ( isset( $this ) && $this instanceof \Codeception\Module\WPLoader ) {
	$config = $this->_getConfig();
	define( 'DB_NAME', $config['dbName'] );
	define( 'DB_HOST', $config['dbHost'] );
	define( 'DB_USER', $config['dbUser'] );
	define( 'DB_PASSWORD', $config['dbPassword'] );
} else {
	/**
	 * If we are not in the context of WPLoader module, this is a WPBrowser request
	 */
	require_once( __DIR__ . '/vendor/autoload.php' );
	$tests_env = \Dotenv\Dotenv::create( __DIR__ . '/dev/tests' );
	$tests_env->load();
	define( 'DB_NAME', getenv( 'ACCEPTANCE_DB_NAME' ) );
	define( 'DB_HOST', getenv( 'ACCEPTANCE_DB_HOST' ) );
	define( 'DB_USER', getenv( 'ACCEPTANCE_DB_USER' ) );
	define( 'DB_PASSWORD', getenv( 'ACCEPTANCE_DB_PASS' ) );
}

//define( 'DOMAIN_CURRENT_SITE', 'square1.tribe' );
//define( 'WP_TESTS_MULTISITE', true );
define( 'WP_TESTS_DOMAIN', 'square1.tribe' );
//define( 'WPCEPT_ISOLATED_INSTALL', true );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', true );
define( 'SAVEQUERIES', false );
define( 'SCRIPT_DEBUG', true );
define( 'WP_CACHE', false );

// Invalidate memcached / redis object caching during testing
define( 'WP_CACHE_KEY_SALT', uniqid() );

if (empty($_SERVER['HTTP_HOST'])) {
	$_SERVER['HTTP_HOST']   = 'square1.tribe';
}
if ( empty($_SERVER['REQUEST_URI']) ) {
	$_SERVER['REQUEST_URI'] = '/';
}
// ==============================================================
// Assign default constant values
// ==============================================================

$config_defaults = array(

	// Paths
	'WP_CONTENT_DIR'          => dirname( __FILE__ ) . '/wp-content',
	'WP_CONTENT_URL'          => 'http://' . $_SERVER['HTTP_HOST'] . '/wp-content',
	'ABSPATH'                 => dirname( __FILE__ ) . '/wp/',

	// Multisite
	//'WP_ALLOW_MULTISITE'    => true,
	//'MULTISITE'             => true,
	//'SUBDOMAIN_INSTALL'     => false,
	//'DOMAIN_CURRENT_SITE'   => '%%PRIMARY_DOMAIN%%',
	//'PATH_CURRENT_SITE'     => '/',
	//'SITE_ID_CURRENT_SITE'  => 1,
	//'BLOG_ID_CURRENT_SITE'  => 1,

	// DB settings
	'DB_CHARSET'              => 'utf8',
	'DB_COLLATE'              => '',

	// Language
	'WPLANG'                  => '',

	// Security Hashes (grab from: https://api.wordpress.org/secret-key/1.1/salt)
	'AUTH_KEY'                => '%%AUTH_KEY%%',
	'SECURE_AUTH_KEY'         => '%%SECURE_AUTH_KEY%%',
	'LOGGED_IN_KEY'           => '%%LOGGED_IN_KEY%%',
	'NONCE_KEY'               => '%%NONCE_KEY%%',
	'AUTH_SALT'               => '%%AUTH_SALT%%',
	'SECURE_AUTH_SALT'        => '%%SECURE_AUTH_SALT%%',
	'LOGGED_IN_SALT'          => '%%LOGGED_IN_SALT%%',
	'NONCE_SALT'              => '%%NONCE_SALT%%',

	// Security Directives
	'DISALLOW_FILE_EDIT'      => true,
	'DISALLOW_FILE_MODS'      => true,
	'FORCE_SSL_LOGIN'         => false,
	'FORCE_SSL_ADMIN'         => false,

	// Performance
	'WP_CACHE'                => true,
	'WP_MEMORY_LIMIT'         => '96M',
	'WP_MAX_MEMORY_LIMIT'     => '256M',
	'EMPTY_TRASH_DAYS'        => 7,
	'WP_MEMCACHED_KEY_SALT'   => 'tribe',

	// Debug
	'WP_DEBUG'                => false,
	'WP_DEBUG_DISPLAY'        => false,
	'SAVEQUERIES'             => false,
	'SCRIPT_DEBUG'            => false,
	'CONCATENATE_SCRIPTS'     => false,
	'COMPRESS_SCRIPTS'        => false,
	'COMPRESS_CSS'            => false,

	// Domain Mapping
	//'SUNRISE'                 => true,

	// Miscellaneous
	'WP_POST_REVISIONS'       => true,
	'WP_DEFAULT_THEME'        => 'core'
);

// ==============================================================
// Use defaults array to define constants where applicable
// ==============================================================

foreach ( $config_defaults AS $config_default_key => $config_default_value ) {
	if ( ! defined( $config_default_key ) )
		define( $config_default_key, $config_default_value );
}

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================

if ( empty( $table_prefix ) )
	$table_prefix = 'tribe_';

// ==============================================================
// Manually back up the WP_DEBUG_DISPLAY directive
// ==============================================================

if ( ! defined( 'WP_DEBUG_DISPLAY' ) || WP_DEBUG_DISPLAY == false )
	ini_set( 'display_errors', 0 );

/*
 * Glomar
 *
 * GLOMAR is a plugin that blocks the frontend of the site from public access.
 * If you would like to disable the plugin locally, add the following to your local-config.php.
 */

define( 'TRIBE_GLOMAR', false );

