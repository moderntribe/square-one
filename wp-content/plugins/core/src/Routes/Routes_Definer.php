<?php declare( strict_types=1 );
/**
 * Defines all regular and REST routes to register.
 *
 * @package Project
 */

namespace Tribe\Project\Routes;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Libs\Routes\Route_Definer;

/**
 * Class to define routes to register.
 */
class Routes_Definer implements Definer_Interface {
	/**
	 * Define routes that need to be registered.
	 *
	 * @return array REST and Regular routes that should be registered.
	 */
	public function define(): array {
		return [
			Route_Definer::ROUTES      => DI\add(
				[
					DI\get( Sample_Route::class ),
				]
			),
			Route_Definer::REST_ROUTES => DI\add(
				[
					DI\get( Sample_REST_Route::class ),
				]
			),
		];
	}
}
