<?php
/*
Plugin Name: Tribe Object Cache Management
Author: Modern Tribe, Inc.
Version: 1.0
Author URI: http://tri.be
Description: Provides a wrapper for object cache as well as a class for managing cache invalidation.
*/

// Block direct requests
if ( ! defined( 'ABSPATH' ) )
	die( '-1' );

if ( ! function_exists( 'tribe_object_cache_load' ) ) {

	function tribe_object_cache_load() {

		require_once( __DIR__ . '/classes/Tribe_ObjectCache.php' );
		require_once( __DIR__ . '/classes/Tribe_ObjectCacheManager.php' );
		add_action( 'plugins_loaded', array( 'Tribe_ObjectCache', 'init' ) );
		add_action( 'plugins_loaded', array( 'Tribe_ObjectCacheManager', 'init' ) );

	}

	tribe_object_cache_load();
}
