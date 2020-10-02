<?php

namespace Tribe\Project\Routes;

use Tribe\Libs\Routes\Abstract_Rest_Route;

class Sample_REST_Route extends Abstract_Rest_Route {
	/**
	 * Registers routes.
	 *
	 * @return void
	 */
	public function register() : void {
		register_rest_route(
			$this->get_project_namespace(),
			'/sample',
			[
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'query' ],
				'args'     => $this->get_supported_args(),
			]
		);
	}
	
	/**
	 * List of valid query parameters supported by the endpoint.
	 *
	 * @return array Valid parameters for this endpoint.
	 */
	public function get_supported_args() : array {
		return [
			'name'          => [
				'type'        => 'string',
				'default'     => '',
				'description' => 'Example argument.',
			],
		];
	}

	/**
	 * Callback for REST endpoint.
	 *
	 * @param \WP_REST_Request $request    The rest request class.
	 * @return \WP_REST_Response|\WP_Error The response object, \WP_Error on failure.
	 */
	public function query( $request ) {
		return rest_ensure_response(
			new \WP_Error(
				'sample_error',
				esc_html__( 'Sample REST Endpoint Error.', 'tribe' )
			)
		);
	}
}
