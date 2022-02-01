<?php declare(strict_types=1);

/*
Plugin Name: Modern Tribe Core Functionality
Description: Core functionality for this site.
Author:      Modern Tribe
Version:     1.0
Author URI:  http://www.tri.be
*/

// Some hosts do not allow sub-folder WP installs, this check will cover multiple conditions.
$tribe_autoloaders = [
	// WP sub folder
	trailingslashit( ABSPATH ) . '../vendor/autoload.php',
	// WP standard
	trailingslashit( ABSPATH ) . 'vendor/autoload.php',
	// In the core plugin
	trailingslashit( __DIR__ ) . 'vendor/autoload.php',
];

foreach ( $tribe_autoloaders as $autoloader ) {
	if ( file_exists( $autoloader ) ) {
		require_once $autoloader;
		break;
	}
}

// Start the core plugin
add_action( 'plugins_loaded', static function (): void {
	tribe_project()->init( __FILE__ );
}, 1, 0 );

/**
 * Shorthand to get the instance of our main core plugin class
 *
 * @return \Tribe\Project\Core
 */
function tribe_project(): \Tribe\Project\Core {
	return \Tribe\Project\Core::instance();
}
