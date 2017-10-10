<?php
/*
Plugin Name: Term Sorter
Description: Drag and drop sorting of taxonomy terms
Author: Modern Tribe, Inc. (Mat Lipe)
Author URI: http://tri.be
Version: 1.0
*/



/**
 * Load all the plugin files and initialize appropriately
 *
 * @return void
 */
if ( !function_exists('term_sorter_load') ) { // play nice
	function term_sorter_load() {
		require( dirname(__FILE__). '/classes/Term_Sorter.php' );
		add_action( 'plugins_loaded', array( 'Term_Sorter', 'init' ) );		
	}

	term_sorter_load();
}


