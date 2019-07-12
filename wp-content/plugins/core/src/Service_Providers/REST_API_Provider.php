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
use Tribe\Project\REST_API\REST_API_Meta_Registration;

/**
 * Class REST_API_Provider
 *
 * @package Tribe\Project\Service_Providers
 */
class REST_API_Provider extends Service_Provider {
	const META = 'rest.meta_registration';

	/**
	 * Registers our class with Pimple.
	 *
	 * @param Container $container The Pimple container.
	 */
	public function register( Container $container ) {
		$container[ self::META ] = function (): REST_API_Meta_Registration {
			return new REST_API_Meta_Registration();
		};

		add_action(
			'rest_api_init',
			function () use ( $container ) {
				$container['rest.meta_registration']->add_meta_to_rest();
			},
			10,
			0
		);
	}
}
