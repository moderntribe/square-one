<?php

/**
 * A library for adding an attachment field to an admin meta box
 */


/**
 * Auto load class files based on namespaced folders
 *
 * @return void
 */
if( !function_exists('attachment_helper_autoload') ){
	function attachment_helper_autoload( $class ){
		$parts = explode('\\', $class);
		if ( $parts[0] == 'AttachmentHelper' ) {
			if( file_exists( dirname(__FILE__).'/'.implode(DIRECTORY_SEPARATOR, $parts).'.php' ) ){
				require_once( dirname(__FILE__).'/'.implode(DIRECTORY_SEPARATOR, $parts).'.php' );
			}
		}
	}
}



/**
 * Load all the plugin files and initialize appropriately
 *
 * @return void
 */
if ( !function_exists('attachment_helper_load') ) { // play nice
	function attachment_helper_load() {
		spl_autoload_register( 'attachment_helper_autoload' );
		if ( defined('DOING_AJAX') && DOING_AJAX ) {
			\AttachmentHelper\Ajax_Handler::init();
		}
	}

	attachment_helper_load();
}
