<?php
/**
 * Settings Route.
 *
 * @package Square1-REST
 */
declare( strict_types=1 );

namespace Tribe\Project\REST\Routes\v1;

use Tribe\Project\Post_Types\Sample\Sample;
use Tribe\Project\REST\Models\Sample_Response_Object;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Class Settings_Route.
 */
class Sample_Route extends Route_v1 {

	// Routes.
	public const GET = 'sample/get';

	/**
	 * Register Settings route.
	 */
	public function register_routes(): void {
		register_rest_route( $this->namespace, self::GET, [
			[
				'methods'  => WP_REST_Server::READABLE,
				'callback' => [ $this, 'get' ],
				'args'     => [],
			],
		] );
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public function get( WP_REST_Request $request ): WP_REST_Response {
		$args = [
			'post_type' => Sample::NAME,
		];

		$query       = new \WP_Query( $args );
		$samples     = $query->get_posts();
		$sample_objs = [];

		// Sample Response Objects may be a composition of multiple data sources, like a post and it's featured image URL.
		foreach ( $samples as $sample ) {
			$sample_objs[] = new Sample_Response_Object( $sample );
		}

		return new WP_REST_Response( $sample_objs, 200 );
	}
}
