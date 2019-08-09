<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Project\Util\SVG_Support;

class Util_Provider extends Service_Provider {

	public function register( Container $container ) {

		$container['util.svg_support'] = function ( $container ) {
			return new SVG_Support();
		};

		add_action( 'init', function () use ( $container ) {
			$container['util.svg_support']->hook();
		}, 10, 0 );
	}
}
