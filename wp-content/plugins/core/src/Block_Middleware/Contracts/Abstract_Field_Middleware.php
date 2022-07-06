<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Contracts;

use Closure;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Project\Block_Middleware\Guards\Block_Field_Middleware_Guard;

/**
 * A pipeline stage to add fields to an existing Block Config.
 *
 * @see Block_Config::add_field()
 */
abstract class Abstract_Field_Middleware implements Field_Middleware {

	protected Block_Field_Middleware_Guard $guard;

	/**
	 * Set additional fields on an existing block.
	 *
	 * @param \Tribe\Libs\ACF\Block_Config $block
	 * @param array                        $params Optional parameters to customize the middleware logic.
	 *
	 * @return \Tribe\Libs\ACF\Block_Config
	 */
	abstract protected function set_fields( Block_Config $block, array $params = [] ): Block_Config;

	public function __construct( Block_Field_Middleware_Guard $guard ) {
		$this->guard = $guard;
	}

	/**
	 * Add fields if this middleware allows it.
	 *
	 * @param \Tribe\Libs\ACF\Block_Config $block
	 * @param \Closure                     $next
	 * @param array                        $params
	 *
	 * @return \Tribe\Libs\ACF\Block_Config
	 */
	public function add_fields( Block_Config $block, Closure $next, array $params = [] ): Block_Config {
		if ( ! $this->guard->allowed( $block, $this ) ) {
			return $next( $block );
		}

		$block = $this->set_fields( $block, $params );

		return $next( $block );
	}

}
