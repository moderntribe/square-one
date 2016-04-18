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
 * Theme Core Functionality "Plugin"
 */

if ( ! function_exists( 'tribe_theme_setup' ) ):

function tribe_theme_setup() {

	// Core & Architecture
	include_once 'functions/core.php';

	// Media
	include_once 'functions/media.php';
	include_once 'functions/embeds.php';

	// Walkers
	include_once 'functions/walkers/nav-clean.php';

	// Localization
	include_once 'functions/localization.php';

	// Template Tags
	include_once 'functions/template-tags/schema.php';
	include_once 'functions/template-tags/content.php';
	include_once 'functions/template-tags/comments.php';
	include_once 'functions/template-tags/panels.php';
	include_once 'functions/template-tags/branding.php';
	
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
	if ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) {
		$css_dir    = trailingslashit( get_template_directory_uri() ) . 'css/admin/dist/';
		$editor_css = 'editor-style.min.css';
	}

	add_editor_style( $css_dir . $editor_css );

}

