<?php
/**
 * Customizations to REST API QOD/SQOD endpoint.
 *
 * @package Square1-REST
 */

declare( strict_types=1 );

namespace Tribe\Project\REST\WP_Endpoints;

use Tribe\Project\Legacy\DB\Tables\Performance_Table;
use Tribe\Project\Legacy\QOD\Models\Performance;
use Tribe\Project\Object_Meta;
use Tribe\Project\Post_Types;
use Tribe\Project\Post_Types\SQOD\SQOD;
use Tribe\Project\REST\Utils\Utils;
use Tribe\Project\Taxonomies\Student_Topic\Student_Topic;
use Tribe\Project\Taxonomies\Topic\Topic;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class QOD_Endpoint.
 */
class Sample_Endpoint {

	// Response keys.
	public const EXAMPLE_KEY = 'example';

	/**
	 * Fire all modifications.
	 *
	 * @param WP_REST_Response $response  The prepared REST response.
	 * @param int              $object_id The ACF ID of the queried object.
	 * @param WP_REST_Request  $request   The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function init( WP_REST_Response $response, int $object_id, WP_REST_Request $request ): WP_REST_Response {
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_data = $response->get_data();

		// Get ACF fields.
		$fields = get_fields( $object_id );

		// Add the fields to the response data.
		if ( $fields ) {
			foreach ( $fields as $field_name => $value ) {
				$response_data[ $field_name ] = $value;
			}
		}

		$response->set_data( $response_data );

		return $response;
	}
}
