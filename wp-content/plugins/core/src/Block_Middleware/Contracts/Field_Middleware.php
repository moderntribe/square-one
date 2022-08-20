<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Contracts;

use Closure;
use Tribe\Libs\ACF\Block_Config;

/**
 * A pipeline stage to add fields to an existing Block Config.
 *
 * @see Block_Config::add_field()
 */
interface Field_Middleware extends Middleware {

	/**
	 * @param \Tribe\Libs\ACF\Block_Config $block  The block to add fields to.
	 * @param \Closure                     $next   The next Field_Middleware in the stack.
	 * @param array<string, string[]>      $params Optional parameters to customize the middleware logic.
	 *
	 * @return \Tribe\Libs\ACF\Block_Config
	 */
	public function add_fields( Block_Config $block, Closure $next, array $params = [] ): Block_Config;

}
