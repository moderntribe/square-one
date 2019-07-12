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
use Tribe\Project\REST_API\Fields\Example_One_Field;

class REST_API_Provider extends Service_Provider {
	const EXAMPLE_FIELD = 'rest.fields.example';

	public function register( Container $container ) {
		$this->fields( $container );
	}

	private function fields( Container $container ) {
		$this->example_field( $container );
	}

	private function example_field( Container $container ) {
		$container[ self::EXAMPLE_FIELD ] = function (): Example_One_Field {
			return new Example_One_Field();
		};

		add_action( 'rest_api_init', function () use ( $container ) {
			$container[ self::EXAMPLE_FIELD ]->register_field();
		}, 0, 0 );
	}
}
