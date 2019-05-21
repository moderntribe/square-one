<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Libs\Assets\Asset_Loader;

class Asset_Provider extends Service_Provider {

	public function register( Container $container ) {

		$container[ 'assets' ] = function ( $container ) {
			return new Asset_Loader( dirname( $container[ 'plugin_file' ] ) . DIRECTORY_SEPARATOR . 'assets' );
		};

		require_once( dirname( $container[ 'plugin_file' ] ) . '/functions/assets.php' );

	}
}