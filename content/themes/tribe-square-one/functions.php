<?php
/**
 * Theme functions
 *
 * @since tribe-square-one 1.0
 */


// Core Theme Plugin
add_action( 'after_setup_theme', 'tribe_theme_setup' );

// Miscellaneous
add_action( 'after_setup_theme', 'visual_editor_styles', 10 );


/**
 * Theme Core Functionalty "Plugin"
 */

if ( ! function_exists( 'tribe_theme_setup' ) ):

function tribe_theme_setup() {

	// Admin
	if ( is_admin() )
		include_once 'functions/admin.php';

	// Media
	include_once 'functions/media.php';

	// Walkers
	include_once 'functions/walkers/nav-clean.php';

	// Template Tags
	include_once 'functions/template-tags/helpers.php';
	include_once 'functions/template-tags/content.php';
	include_once 'functions/template-tags/pagination.php';
	include_once 'functions/template-tags/logo.php';
	
	// Theme
	include_once 'functions/theme.php';
	include_once 'functions/resources.php';
	
}

endif; // tribe_theme_setup


/**
 * Visual Editor Styles
 */

function visual_editor_styles() {

	$css_dir    = trailingslashit( get_template_directory_uri() ) . 'css/admin/';
    $editor_css = 'editor-style.css';

    // Production
    if ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false )
        $css_dir = trailingslashit( get_template_directory_uri() ) . 'css/admin/dist/';

	add_editor_style( $css_dir . 'editor-style.css' );

}

