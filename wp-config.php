<?php

// Load configuration values from the environment
require __DIR__ . '/wp-config-environment.php';

// The following is loaded for browser requests (including acceptance tests),
// but not integration tests

foreach ( [ 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST' ] as $dbvar ) {
	if ( ! defined( $dbvar ) ) {
		define( $dbvar, tribe_getenv( $dbvar, '' ) );
	}
}

// Bootstrap WordPress
require_once ABSPATH . 'wp-settings.php';
