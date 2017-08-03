<?php
namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\BCIT_Demo\Example;

class BCIT_Demo_Service_Provider implements ServiceProviderInterface {
	public function register( Container $container ) {
		$container[ 'bcit-demo.example' ] = function ( $container ) {
			return new Example();
		};

		add_filter( 'upload_mimes', function( $mime_types ) use ( $container ) {
			return $container[ 'bcit-demo.example' ]->allowed_mime_types( $mime_types );
		} );
	}
}