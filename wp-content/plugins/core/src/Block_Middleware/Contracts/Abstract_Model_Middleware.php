<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Contracts;

use Closure;
use Tribe\Project\Block_Middleware\Guards\Block_Model_Middleware_Guard;
use Tribe\Project\Blocks\Contracts\Model;

/**
 * A pipeline stage to add data to an existing Block Model.
 *
 * @see \Tribe\Project\Blocks\Types\Base_Model::get_data()
 */
abstract class Abstract_Model_Middleware implements Model_Middleware {

	protected Block_Model_Middleware_Guard $guard;

	/**
	 * Append additional data to the block model.
	 *
	 * @param \Tribe\Project\Blocks\Contracts\Model $model
	 *
	 * @return \Tribe\Project\Blocks\Contracts\Model
	 */
	abstract protected function append_data( Model $model ): Model;

	public function __construct( Block_Model_Middleware_Guard $guard ) {
		$this->guard = $guard;
	}

	/**
	 * @param \Tribe\Project\Blocks\Contracts\Model $model The model to append data to.
	 * @param \Closure                              $next The next Model_Middleware in the stack.
	 *
	 * @return \Tribe\Project\Blocks\Contracts\Model
	 */
	public function set_data( Model $model, Closure $next ): Model {
		if ( ! $this->guard->allowed( $model, $this ) ) {
			return $next( $model );
		}

		$model = $this->append_data( $model );

		return $next( $model );
	}

}
