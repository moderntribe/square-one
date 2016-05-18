<?php
/**
 * Theme functions
 *
 * @since core 1.0
 */


// Core Theme Plugin
add_action( 'after_setup_theme', 'core_theme_setup' );

// Miscellaneous
add_action( 'after_setup_theme', 'visual_editor_styles', 10 );
add_filter( 'tiny_mce_before_init', 'visual_editor_body_class' );


/**
 * Theme Core Functionality "Plugin"
 */

if ( ! function_exists( 'core_theme_setup' ) ):

function core_theme_setup() {

	// Core & Architecture
	include_once 'functions/core.php';

	// Template Tags
	include_once 'functions/template-tags/schema.php';
	include_once 'functions/template-tags/content.php';
	include_once 'functions/template-tags/comments.php';
	include_once 'functions/template-tags/panels.php';
	include_once 'functions/template-tags/branding.php';
	
}

endif; // core_theme_setup


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


/**
 * Visual Editor Body Class
 */

function visual_editor_body_class( $settings ) {

    $settings['body_class'] .= ' context-content';

    return $settings;

}