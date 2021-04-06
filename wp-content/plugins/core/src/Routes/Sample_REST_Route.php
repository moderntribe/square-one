<?php declare( strict_types=1 );
/**
 * Sample REST route.
 *
 * @package Project
 */

namespace Tribe\Project\Routes;

use Tribe\Libs\Routes\Abstract_REST_Route;

/**
 * Class for an example REST route.
 */
class Sample_REST_Route extends Abstract_REST_Route {
	/**
	 * Registers routes.
	 *
	 * @return void
	 */
	public function register(): void {
		register_rest_route(
			$this->get_project_namespace(),
			'/sample',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'query' ],
				'args'                => $this->get_supported_args(),
				'permission_callback' => '__return_true',
			]
		);
	}
	
	/**
	 * List of valid query parameters supported by the endpoint.
	 *
	 * @return array Valid parameters for this endpoint.
	 */
	public function get_supported_args(): array {
		return [
			'name'          => [
				'type'        => 'string',
				'default'     => '',
				'description' => __( 'Example argument.', 'tribe' ),
			],
		];
	}

	/**
	 * Callback for REST endpoint.
	 *
	 * Example: https://square1.tribe/wp-json/tribe/v1/sample/?name=test
	 *
	 * @param \WP_REST_Request $request    The rest request class.
	 * @return \WP_REST_Response|\WP_Error The response object, \WP_Error on failure.
	 */
	public function query( $request ) {
		return rest_ensure_response(
			new \WP_Error(
				'sample_error',
				sprintf(
					esc_html__( 'Sample REST Endpoint Error. Params: {name: %1$s}', 'tribe' ),
					$request->get_param( 'name' )
				)
			)
		);
	}
}
