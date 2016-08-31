<?php
/*
Plugin Name: JSON for Linking Data
Description: Prints JSON-LD data for each page
Author: Modern Tribe, Inc.
Author URI: http://tri.be
Version: 1.0
*/

namespace JSONLD;

include __DIR__ . '/vendor/autoload.php';

/**
 * Load all the plugin files and initialize appropriately
 *
 * @return void
 */
if ( ! function_exists( 'json_ld_load' ) ) { // play nice
	function json_ld_load() {

		require_once( __DIR__ . '/vendor/flightless/attachment-helper/attachment-helper.php');

		Settings_Page::init();

		add_action( 'wp_footer', function () {
			$org_data    = new Organization_Data_Builder( null );
			$page_schema = new Page_Schema();
			$printer     = new Data_Printer();
			$printer->print_data( $org_data->get_data() );
			$printer->print_data( $page_schema->get_data() );
		} );

		// Remove WP SEO json-ld output
		add_filter( 'wpseo_json_ld_output', '__return_false' );

	}

	json_ld_load();
}




