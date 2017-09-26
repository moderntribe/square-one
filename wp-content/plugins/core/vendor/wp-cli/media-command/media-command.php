<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

$autoload = dirname( __FILE__ ) . '/vendor/autoload.php';
if ( file_exists( $autoload ) ) {
	require_once $autoload;
}

WP_CLI::add_command( 'media', 'Media_Command', array(
	'before_invoke' => function () {
		if ( !wp_image_editor_supports() ) {
			WP_CLI::error( 'No support for generating images found. ' .
				'Please install the Imagick or GD PHP extensions.' );
		}
	}
) );
