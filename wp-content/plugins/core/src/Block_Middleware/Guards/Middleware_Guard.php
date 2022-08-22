<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Guards;

use Ds\Map;
use Tribe\Project\Block_Middleware\Contracts\Middleware;

/**
 * Determine if middleware can be applied to a specific block or block model.
 *
 * @see \Tribe\Libs\ACF\Block_Config
 * @see \Tribe\Project\Blocks\Contracts\Model
 */
class Middleware_Guard {

	protected Map $allowed_items;

	public function __construct( Map $allowed_items ) {
		$this->allowed_items = $allowed_items;
	}

	/**
	 * Whether this block or model is allowed to have the current middleware being processed applied to it.
	 *
	 * @param \Tribe\Libs\ACF\Block_Config|\Tribe\Project\Blocks\Contracts\Model $class_instance
	 * @param \Tribe\Project\Block_Middleware\Contracts\Middleware $current_middleware
	 *
	 * @return bool
	 */
	public function allowed( $class_instance, Middleware $current_middleware ): bool {
		$key = get_class( $class_instance );

		if ( ! $this->allowed_items->hasKey( $key ) ) {
			return false;
		}

		$result = $this->allowed_items->get( $key );

		if ( is_array( $result ) ) {
			return $this->process_specific_middleware( $result, $current_middleware );
		}

		return (bool) $result;
	}

	/**
	 * This block or model only allows middleware from specific implementations.
	 *
	 * @param class-string[]                                       $allowed_middleware
	 * @param \Tribe\Project\Block_Middleware\Contracts\Middleware $current_middleware
	 *
	 * @return bool
	 */
	protected function process_specific_middleware( array $allowed_middleware, Middleware $current_middleware ): bool {
		$class = get_class( $current_middleware );

		foreach ( $allowed_middleware as $middleware ) {
			if ( $class === $middleware ) {
				return true;
			}
		}

		return false;
	}

}
