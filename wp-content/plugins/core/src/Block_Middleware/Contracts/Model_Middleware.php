<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Contracts;

use Closure;
use Tribe\Project\Blocks\Contracts\Model;

/**
 * A pipeline stage to add model data to an existing Block Model.
 *
 * @see \Tribe\Project\Blocks\Types\Base_Model::get_data()
 */
interface Model_Middleware extends Middleware {

	/**
	 * @param \Tribe\Project\Blocks\Contracts\Model $model The model to append data to.
	 * @param \Closure                              $next The next Model_Middleware in the stack.
	 *
	 * @return \Tribe\Project\Blocks\Contracts\Model
	 */
	public function set_data( Model $model, Closure $next ): Model;

}
