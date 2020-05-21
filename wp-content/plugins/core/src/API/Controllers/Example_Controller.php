<?php
/**
 * Example route controller.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Controllers;

/**
 * Class Example_Controller.
 */
class Example_Controller extends Controller {
	/**
	 * @inheritDoc
	 */
	public function handle_request( array $args = [] ): array {
		$this->success( [] );
	}
}
