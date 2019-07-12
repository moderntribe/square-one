<?php
/**
 * The service provider that adds all of the REST_API hooks.
 *
 * @package Tribe\Project\REST_API
 */

declare( strict_types=1 );

namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Project\REST_API\REST_API_Main;

class REST_API_Provider extends Service_Provider {
	const MAIN = 'rest.main';

	public function register( Container $container ) {
		$container[ self::MAIN ] = function (): REST_API_Main {
			return new REST_API_Main();
		};

		add_action(
			'rest_api_init',
			function () use ( $container ) {
				$container['rest.main']->hook();
			},
			0,
			0
		);
	}
}
