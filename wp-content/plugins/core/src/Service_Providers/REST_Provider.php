<?php
/**
 * The service provider for REST API endpoint modifications.
 *
 * @package AAN
 */

declare( strict_types=1 );

namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Project\Post_Types\Sample\Sample;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class REST_API_Provider.
 */
class REST_Provider extends Service_Provider {

	// Container keys.
	public const SAMPLE_EP = 'rest_api.sample_endpoint';

	// v1.
	public const SAMPLE_RT = 'rest_api.v1.sample_route';

	/**
	 * Register function, which adds the service to our container along with relevant hooks.
	 *
	 * @param Container $container The Pimple container.
	 */
	public function register( Container $container ): void {
		// Register custom routes.
		add_action( 'rest_api_init', static function () use ( $container ) {
			$container[ self::SAMPLE_RT ]->register_routes();
		} );

		// Filter endpoint return.
		add_filter( 'rest_prepare_' . Sample::NAME, static function ( WP_REST_Response $response, object $object, WP_REST_Request $request ) use ( $container ) {
			return $container[ self::SAMPLE_EP ]->init( $response, $object->ID, $request );
		}, 10, 3 );
	}
}
