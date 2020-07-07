<?php
/*
Plugin Name: Modern Tribe Core Functionality
Description: Core functionality for this site.
Author:      Modern Tribe
Version:     1.0
Author URI:  http://www.tri.be
*/

// Some hosts do not allow sub-folder WP installs, this check will cover multiple conditions.
if ( file_exists( ABSPATH . '../vendor/autoload.php' ) ) {
	// WP subfolder
	require_once ABSPATH . '../vendor/autoload.php';
} elseif ( @file_exists( ABSPATH . 'vendor/autoload.php'  ) ) {
	// WP standard
	require_once ABSPATH . 'vendor/autoload.php';
} elseif ( @file_exists( trailingslashit(__DIR__ ) . 'vendor/autoload.php'  ) ) {
	// In core plugin
	require_once trailingslashit(__DIR__ ) . 'vendor/autoload.php';
}
require_once trailingslashit( __DIR__ ) . 'functions/pluggable.php';

// Start the core plugin
add_action( 'plugins_loaded', function () {
	tribe_project()->init( __FILE__ );
}, 1, 0 );

/**
 * Shorthand to get the instance of our main core plugin class
 *
 * @return \Tribe\Project\Core
 */
function tribe_project() {
	return \Tribe\Project\Core::instance();
}

function wpdocs_theme_slug_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Main Sidebar', 'textdomain' ),
        'id'            => 'main',
        'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'textdomain' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'wpdocs_theme_slug_widgets_init' );
