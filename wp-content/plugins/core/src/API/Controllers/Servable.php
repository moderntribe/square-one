<?php
/**
 * A route controller.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Controllers;

/**
 * Interface Servable.
 */
interface Servable {

	/**
	 * Handle a request.
	 *
	 * @param array $args The request arguments.
	 *
	 * @return mixed
	 */
	public function handle_request( array $args = [] );
}
