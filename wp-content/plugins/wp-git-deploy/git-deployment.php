<?php
/*
Plugin Name: Git Deployment
Plugin URI: https://github.com/jbrinley/wp-git-deploy
Description: Deploy updates with git
Author: Modern Tribe, Inc.
Author URI: http://tri.be
Contributors: jbrinley
Version: 1.0
*/

/**
 * Load all the plugin files and initialize appropriately
 *
 * @return void
 */
if ( !function_exists('git_info_load') ) { // play nice
	function git_info_load() {
		require_once('Git_Info_Plugin.php');
		require_once('Git_Deploy.php');
		require_once('template-tags.php');
		Git_Info_Plugin::init();
		Git_Deploy::init();
	}

	git_info_load();
}
