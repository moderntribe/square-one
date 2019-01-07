<?php
/*
Plugin Name: Modern Tribe Core Functionality
Description: Core functionality for this site.
Author:      Modern Tribe
Version:     1.0
Author URI:  http://www.tri.be
*/

require_once ABSPATH . '../vendor/autoload.php';
require_once trailingslashit( __DIR__ ) . 'functions/pluggable.php';

// Start the core plugin
add_action( 'plugins_loaded', function () {
	tribe_project()->init();
}, 1, 0 );

/**
 * Shorthand to get the instance of our main core plugin class
 *
 * @return \Tribe\Project\Core
 */
function tribe_project() {
	return \Tribe\Project\Core::instance( new \Tribe\Project\Container\Container( [ 'plugin_file' => __FILE__ ]) );
}
