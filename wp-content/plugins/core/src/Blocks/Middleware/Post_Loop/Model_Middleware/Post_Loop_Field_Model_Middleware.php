<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop\Model_Middleware;

use DI\FactoryInterface;
use Tribe\Project\Block_Middleware\Contracts\Abstract_Model_Middleware;
use Tribe\Project\Block_Middleware\Guards\Block_Model_Middleware_Guard;
use Tribe\Project\Blocks\Contracts\Model;
use Tribe\Project\Blocks\Middleware\Post_Loop\Field_Middleware\Post_Loop_Field_Middleware;
use Tribe\Project\Blocks\Middleware\Post_Loop\Models\Post_Loop_Model;
use Tribe\Project\Blocks\Middleware\Post_Loop\Post_Loop_Controller;

/**
 * Inject data into a block's model.
 */
class Post_Loop_Field_Model_Middleware extends Abstract_Model_Middleware {

	protected FactoryInterface $container;

	public function __construct( Block_Model_Middleware_Guard $guard, FactoryInterface $container ) {
		parent::__construct( $guard );

		$this->container = $container;
	}

	/**
	 * Merge the post loop controller into a model's data, so it can be used in that
	 * block's controller.
	 *
	 * The block's controller must have `posts` class property in order to accept the model data.
	 *
	 * @param \Tribe\Project\Blocks\Contracts\Model $model
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @return \Tribe\Project\Blocks\Contracts\Model
	 */
	protected function append_data( Model $model ): Model {
		$fields = (array) get_fields();

		if ( ! isset( $fields[ Post_Loop_Field_Middleware::NAME ] ) ) {
			return $model;
		}

		$post_loop_model = new Post_Loop_Model( $fields[ Post_Loop_Field_Middleware::NAME ] );

		$controller = $this->container->make( Post_Loop_Controller::class, [
			'model' => $post_loop_model,
		] );

		$model->set_data( array_merge( $model->get_data(), [
			Post_Loop_Controller::POSTS => $controller->get_posts(),
		] ) );

		return $model;
	}

}
