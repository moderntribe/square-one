<?php declare( strict_types=1 );
/**
 * Subscribe routes to WP lifecycle hooks.
 *
 * @package Project
 */

namespace Tribe\Project\Routes;

use Tribe\Libs\Container\Abstract_Subscriber;

/**
 * Subscribes routes to WP lifecycle hooks.
 */
class Routes_Subscriber extends Abstract_Subscriber {
	/**
	 * Router version number.
	 *
	 * @var float
	 */
	protected const VERSION = '1.1.1';

	/**
	 * Registers any WP lifecycle hooks for routes.
	 *
	 * @return void
	 */
	public function register(): void {
		// add_filter(
		// 	'core_js_config',
		// 	function ( ...$args ): array {
		// 		return $this->container->get( Sample_Route::class )->js_config( ...$args );
		// 	},
		// 	10,
		// 	1
		// );

		add_filter(
			'tribe_libs_router_version',
			function ( ...$args ) {
				return self::VERSION;
			},
			10,
			1
		);
	}
}
