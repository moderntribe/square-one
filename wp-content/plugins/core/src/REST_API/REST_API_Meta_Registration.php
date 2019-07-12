<?php
/**
 * REST API meta registration.
 *
 * @package Tribe\Project\REST_API
 * @version 0.1.0
 */

declare( strict_types=1 );

namespace Tribe\Project\REST_API;

use Tribe\Project\Object_Meta\Example;
use Tribe\Project\Post_Types\Sample\Sample;

/**
 * Class REST_API.
 */
class REST_API_Meta_Registration {

	/**
	 * Adds object meta to the REST API.
	 *
	 * Note that your object meta key will be how this outputs in the REST API, so name your meta accordingly.
	 */
	public function add_meta_to_rest() {
		register_rest_field(
			Sample::NAME,
			Example::ONE,
			[
				'get_callback' => [ $this, 'get_post_meta' ],
				'schema'       => null,
			]
		);
	}

	/**
	 * Gets a meta value by the key for addition to the REST output.
	 *
	 * @param array $object The requested object.
	 * @param string $field_name The name of the field.
	 * @param array $request The request itself.
	 *
	 * @return array
	 */
	public function get_post_meta( array $object = [], $field_name = '', $request = [] ) {
		return get_post_meta( $object['id'], $field_name, true );
	}
}
