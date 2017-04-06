<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Util\SVG_Support;

class Util_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$container[ 'util.svg_support' ] = function ( $container ) {
			return new SVG_Support();
		};

		add_action( 'init', function () use ( $container ) {
			$container[ 'util.svg_support' ]->hook();
		}, 10, 0 );
	}
}