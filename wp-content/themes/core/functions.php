<?php
/**
 * Theme functions
 *
 * @since core 1.0
 */


// Core Theme Plugin
add_action( 'after_setup_theme', 'core_theme_setup' );

/**
 * Theme Core Functionality "Plugin"
 */

if ( ! function_exists( 'core_theme_setup' ) ):

function core_theme_setup() {

	// Template Tags
	include_once 'template-tags/titles.php';
	include_once 'template-tags/images.php';
	include_once 'template-tags/comments.php';
	include_once 'template-tags/branding.php';

	// Remove WP SEO json-ld output in favor of the included functions
	add_filter( 'wpseo_json_ld_output', '__return_false' );
	
}

endif; // core_theme_setup

$args = array(
	'name'          => __( 'Sidebar name', 'theme_text_domain' ),
	'id'            => 'sidebar-main',
	'description'   => '',
        'class'         => '',
	'before_widget' => '<li id="%1$s" class="widget %2$s">',
	'after_widget'  => '</li>',
	'before_title'  => '<h2 class="widgettitle">',
	'after_title'   => '</h2>' );

register_sidebar( $args );