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

