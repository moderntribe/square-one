<?php
/**
 * Base environment configuration, loaded for all environments (including automated tests)
 */

function tribe_isSSL() {
	return ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' );
}

function tribe_getenv( $name, $default = null ) {
	$env = getenv( $name );
	if ( $env === false ) {
		return $default;
	}

	$env_str = strtolower( trim( $env ) );
	if ( $env_str === 'false' || $env_str === 'true' ) {
		return filter_var( $env_str, FILTER_VALIDATE_BOOLEAN );
	}

	if ( is_numeric( $env ) ) {
		return ( $env - 0 );
	}

	return $env;
}


if ( file_exists( __DIR__ . '/.env' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
	$dotenv = Dotenv\Dotenv::create( __DIR__ );
	$dotenv->load();
}

// ==============================================================
// Load build process timestamp
// ==============================================================

if ( file_exists( dirname( __FILE__ ) . '/build-process.php' ) ) {
	include( dirname( __FILE__ ) . '/build-process.php' );
}


if ( file_exists( __DIR__ . '/local-config.php' ) ) {
	include __DIR__ . '/local-config.php';
}


// ==============================================================
// Assign default constant values
// ==============================================================

if ( ! isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) {
	$_SERVER['HTTP_X_FORWARDED_PROTO'] = '';
}
if ( ! isset( $_SERVER['HTTP_HOST'] ) ) {
	$_SERVER['HTTP_HOST'] = 'local-cli';
}

if ( $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
	$_SERVER['HTTPS']       = 'on';
	$_SERVER['SERVER_PORT'] = 443;
}

// ==============================================================
// If a Load Balancer or Proxy is used, X-Forwarded-For HTTP Header to get the users real IP address
// ==============================================================

if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
	$http_x_headers = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );

	$_SERVER['REMOTE_ADDR'] = $http_x_headers[0];
}

$config_defaults = [

	// Paths
	'WP_CONTENT_DIR'                 => tribe_getenv( 'WP_CONTENT_DIR', __DIR__ . '/wp-content' ),
	'WP_CONTENT_URL'                 => tribe_getenv( 'WP_CONTENT_URL', ( tribe_isSSL() ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . '/wp-content' ),
	'ABSPATH'                        => tribe_getenv( 'ABSPATH', __DIR__ . '/wp/' ),
	'TWIG_CACHE_DIR'                 => tribe_getenv( 'TWIG_CACHE', '' ),

	// Multisite
	'WP_ALLOW_MULTISITE'             => tribe_getenv( 'WP_ALLOW_MULTISITE', false ),
	'MULTISITE'                      => tribe_getenv( 'WP_MULTISITE', false ),
	'SUBDOMAIN_INSTALL'              => tribe_getenv( 'SUBDOMAIN_INSTALL', false ),
	'DOMAIN_CURRENT_SITE'            => tribe_getenv( 'DOMAIN_CURRENT_SITE', '%%PRIMARY_DOMAIN%%' ),
	'PATH_CURRENT_SITE'              => tribe_getenv( 'PATH_CURRENT_SITE', '/' ),
	'SITE_ID_CURRENT_SITE'           => tribe_getenv( 'SITE_ID_CURRENT_SITE', 1 ),
	'BLOG_ID_CURRENT_SITE'           => tribe_getenv( 'BLOG_ID_CURRENT_SITE', 1 ),

	// DB settings
	'DB_CHARSET'                     => 'utf8',
	'DB_COLLATE'                     => '',

	// Language
	'WPLANG'                         => tribe_getenv( 'WPLANG', '' ),

	// Security Hashes (grab from: https://api.wordpress.org/secret-key/1.1/salt)
	'AUTH_KEY'                       => '%%AUTH_KEY%%',
	'SECURE_AUTH_KEY'                => '%%SECURE_AUTH_KEY%%',
	'LOGGED_IN_KEY'                  => '%%LOGGED_IN_KEY%%',
	'NONCE_KEY'                      => '%%NONCE_KEY%%',
	'AUTH_SALT'                      => '%%AUTH_SALT%%',
	'SECURE_AUTH_SALT'               => '%%SECURE_AUTH_SALT%%',
	'LOGGED_IN_SALT'                 => '%%LOGGED_IN_SALT%%',
	'NONCE_SALT'                     => '%%NONCE_SALT%%',

	// Security Directives
	'DISALLOW_FILE_EDIT'             => true,
	'DISALLOW_FILE_MODS'             => true,
	'FORCE_SSL_LOGIN'                => tribe_getenv( 'FORCE_SSL_LOGIN', true ),
	'FORCE_SSL_ADMIN'                => tribe_getenv( 'FORCE_SSL_ADMIN', true ),

	// Performance
	'WP_CACHE'                       => tribe_getenv( 'WP_CACHE', false ),
	'DISABLE_WP_CRON'                => tribe_getenv( 'DISABLE_WP_CRON', true ),
	// We always disable cron on large installs
	'WP_MEMORY_LIMIT'                => tribe_getenv( 'WP_MEMORY_LIMIT', '96M' ),
	'WP_MAX_MEMORY_LIMIT'            => tribe_getenv( 'WP_MAX_MEMORY_LIMIT', '256M' ),
	'EMPTY_TRASH_DAYS'               => tribe_getenv( 'EMPTY_TRASH_DAYS', 7 ),
	'WP_APC_KEY_SALT'                => tribe_getenv( 'WP_APC_KEY_SALT', 'tribe' ),
	'WP_MEMCACHED_KEY_SALT'          => tribe_getenv( 'WP_MEMCACHED_KEY_SALT', 'tribe' ),

	// Debug
	'WP_DEBUG'                       => tribe_getenv( 'WP_DEBUG', true ),
	'WP_DEBUG_LOG'                   => tribe_getenv( 'WP_DEBUG_LOG', true ),
	'WP_DEBUG_DISPLAY'               => tribe_getenv( 'WP_DEBUG_DISPLAY', true ),
	'SAVEQUERIES'                    => tribe_getenv( 'SAVEQUERIES', true ),
	'SCRIPT_DEBUG'                   => tribe_getenv( 'SCRIPT_DEBUG', false ),
	'CONCATENATE_SCRIPTS'            => tribe_getenv( 'CONCATENATE_SCRIPTS', false ),
	'COMPRESS_SCRIPTS'               => tribe_getenv( 'COMPRESS_SCRIPTS', false ),
	'COMPRESS_CSS'                   => tribe_getenv( 'COMPRESS_CSS', false ),
	'WP_DISABLE_FATAL_ERROR_HANDLER' => tribe_getenv( 'WP_DISABLE_FATAL_ERROR_HANDLER', true ),

	// Domain Mapping
	//'SUNRISE'                 => true,

	// Miscellaneous
	'WP_POST_REVISIONS'              => tribe_getenv( 'WP_POST_REVISIONS', true ),
	'WP_DEFAULT_THEME'               => tribe_getenv( 'WP_DEFAULT_THEME', 'core' ),

	// S3
	'S3_UPLOADS_BUCKET'              => tribe_getenv( 'S3_UPLOADS_BUCKET', '' ),
	'S3_UPLOADS_KEY'                 => tribe_getenv( 'S3_UPLOADS_KEY', '' ),
	'S3_UPLOADS_SECRET'              => tribe_getenv( 'S3_UPLOADS_SECRET', '' ),
	'S3_UPLOADS_REGION'              => tribe_getenv( 'S3_UPLOADS_REGION', '' ),

	// Glomar
	'TRIBE_GLOMAR'                   => tribe_getenv( 'TRIBE_GLOMAR', '' ),
];

// ==============================================================
// Assign default constant value overrides for production
// ==============================================================

if ( defined( 'ENVIRONMENT' ) && ENVIRONMENT === 'PRODUCTION' ) {
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
	if ( ! defined( $config_default_key ) ) {
		define( $config_default_key, $config_default_value );
	}
}

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================

if ( empty( $table_prefix ) ) {
	$table_prefix = tribe_getenv( 'DB_TABLE_PREFIX', 'tribe_' );
}

// ==============================================================
// Manually back up the WP_DEBUG_DISPLAY directive
// ==============================================================

if ( ! defined( 'WP_DEBUG_DISPLAY' ) || ! WP_DEBUG_DISPLAY ) {
	ini_set( 'display_errors', 0 );
}
