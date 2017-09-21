<?php
/**
 * Modern Tribe Skeleton configuration
 * Based on Mark Jaquith's Skeleton repository
 * @link https://github.com/markjaquith/WordPress-Skeleton
 */


if ( file_exists( __DIR__ . "/.env" ) ) {
	require_once __DIR__ . "/vendor/autoload.php";
	$dotenv = new Dotenv\Dotenv(__DIR__);
	$dotenv->load();
}

// ==============================================================
// Load database info and local development parameters
// ==============================================================

if ( file_exists( dirname( __FILE__ ) . '/local-config.php' ) )
	include( dirname( __FILE__ ) . '/local-config.php' );

// ==============================================================
// Assign default constant values
// ==============================================================

if ( !isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ) $_SERVER['HTTP_X_FORWARDED_PROTO'] = '';
if ( !isset($_SERVER['HTTP_HOST']) ) $_SERVER['HTTP_HOST'] = 'local-cli';

if ( $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
	$_SERVER['HTTPS'] = 'on';
	$_SERVER['SERVER_PORT'] = 443;
}

function tribe_isSSL() {
	if ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) return true;
	return false;
}

function tribe_getenv($name, $default = null) {
	$env = getenv($name);
	if ( $env === false ) return $default;
	if ( $env == "false" || $env == "true" ) return filter_var($env, FILTER_VALIDATE_BOOLEAN);
	if ( is_numeric($env) ) return ($env - 0);
	return $env;
}

$config_defaults = array(

	// Paths
	'WP_CONTENT_DIR'          => dirname( __FILE__ ) . '/wp-content',
	'WP_CONTENT_URL'          => (tribe_isSSL() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/wp-content',
	'ABSPATH'                 => dirname( __FILE__ ) . '/wp/',

	// Multisite
	'WP_ALLOW_MULTISITE'    	=> tribe_getenv('WP_ALLOW_MULTISITE', false),
	'MULTISITE'             	=> tribe_getenv('WP_MULTISITE', false),
	'SUBDOMAIN_INSTALL'     	=> tribe_getenv('SUBDOMAIN_INSTALL', false),
	'DOMAIN_CURRENT_SITE'   	=> tribe_getenv('DOMAIN_CURRENT_SITE', '%%PRIMARY_DOMAIN%%'),
	'PATH_CURRENT_SITE'     	=> tribe_getenv('PATH_CURRENT_SITE', '/'),
	'SITE_ID_CURRENT_SITE'  	=> tribe_getenv('SITE_ID_CURRENT_SITE', 1),
	'BLOG_ID_CURRENT_SITE'  	=> tribe_getenv('BLOG_ID_CURRENT_SITE', 1),

	// DB settings
	'DB_CHARSET'              => 'utf8',
	'DB_COLLATE'              => '',
	'DB_NAME'                 => tribe_getenv('DB_NAME', ''),
	'DB_USER'                 => tribe_getenv('DB_USER', ''),
	'DB_PASSWORD'             => tribe_getenv('DB_PASSWORD', ''),
	'DB_HOST'                 => tribe_getenv('DB_HOST', ''),

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
	'WP_CACHE'                => false,
	'DISABLE_WP_CRON'         => true, // We always disable cron on large installs
	'WP_MEMORY_LIMIT'         => '96M',
	'WP_MAX_MEMORY_LIMIT'     => '256M',
	'EMPTY_TRASH_DAYS'        => 7,
	'WP_APC_KEY_SALT'         => 'tribe',
	'WP_MEMCACHED_KEY_SALT'   => 'tribe',

	// Dynamic Image Resizing
	'FILE_CACHE_MAX_FILE_AGE' => 315000000, // about 10 years

	// Debug
	'WP_DEBUG'                => tribe_getenv('WP_DEBUG', true),
	'WP_DEBUG_LOG'            => tribe_getenv('WP_DEBUG_LOG', true),
	'WP_DEBUG_DISPLAY'        => tribe_getenv('WP_DEBUG_DISPLAY', true),
	'SAVEQUERIES'             => tribe_getenv('SAVEQUERIES', true),
	'SCRIPT_DEBUG'            => tribe_getenv('SCRIPT_DEBUG', true),
	'CONCATENATE_SCRIPTS'     => tribe_getenv('CONCATENATE_SCRIPTS', false),
	'COMPRESS_SCRIPTS'        => tribe_getenv('COMPRESS_SCRIPTS', false),
	'COMPRESS_CSS'            => tribe_getenv('COMPRESS_CSS', false),

	// Domain Mapping
	//'SUNRISE'                 => true,

	// Miscellaneous
	'WP_POST_REVISIONS'       => true,
	'WP_DEFAULT_THEME'        => 'core',

	// S3
	'S3_UPLOADS_BUCKET'       => tribe_getenv('S3_UPLOADS_BUCKET', ''),
	'S3_UPLOADS_KEY'          => tribe_getenv('S3_UPLOADS_KEY', ''),
	'S3_UPLOADS_SECRET'       => tribe_getenv('S3_UPLOADS_SECRET', ''),
	'S3_UPLOADS_REGION'       => tribe_getenv('S3_UPLOADS_REGION', ''),

	// Glomar
	'TRIBE_GLOMAR' 						=> tribe_getenv('TRIBE_GLOMAR', '')
);

// ==============================================================
// Assign default constant value overrides for production
// ==============================================================

if ( defined( 'ENVIRONMENT' ) && ENVIRONMENT == 'PRODUCTION' ) {
	$config_defaults['WP_CACHE']            = true;
	$config_defaults['WP_DEBUG']            = false;
	$config_defaults['WP_DEBUG_LOG']        = false;
	$config_defaults['WP_DEBUG_DISPLAY']    = false;
	$config_defaults['SAVEQUERIES']         = false;
	$config_defaults['CONCATENATE_SCRIPTS'] = true;
	$config_defaults['COMPRESS_SCRIPTS']    = true;
	$config_defaults['COMPRESS_CSS']        = true;
}

// ==============================================================
// Use defaults array to define constants where applicable
// ==============================================================

foreach ( $config_defaults AS $config_default_key => $config_default_value ) {
	if ( ! defined( $config_default_key ) )
		define( $config_default_key, $config_default_value );
}
foreach ($_ENV as $key => $value) {
	if ( ! defined( key ) )
		define( $key, tribe_getenv($key) );
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

// ==============================================================
// Load a Memcached config if we have one
// ==============================================================

if ( file_exists( dirname( __FILE__ ) . '/memcached.php' ) )
	$memcached_servers = include( dirname( __FILE__ ) . '/memcached.php' );

// ==============================================================
// Load a Cache config if we have one
// ==============================================================

if ( file_exists( dirname( __FILE__ ) . '/cache-config.php' ) )
	include( dirname( __FILE__ ) . '/cache-config.php' );

// ==============================================================
// Bootstrap WordPress
// ==============================================================

require_once( ABSPATH . 'wp-settings.php' );
