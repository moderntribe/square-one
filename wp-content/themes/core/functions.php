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

	// @Jonathan
	// Port Me To Core
	include_once 'port-me-to-core/Menus.php';
	include_once 'port-me-to-core/Social.php';
	include_once 'port-me-to-core/Embeds.php';
	include_once 'port-me-to-core/Forms.php';

	// Template Tags
	include_once 'template-tags/titles.php';
	include_once 'template-tags/images.php';
	include_once 'template-tags/comments.php';
	include_once 'template-tags/branding.php';

	// Remove WP SEO json-ld output in favor of the included functions
	add_filter( 'wpseo_json_ld_output', '__return_false' );
	
}

endif; // core_theme_setup

