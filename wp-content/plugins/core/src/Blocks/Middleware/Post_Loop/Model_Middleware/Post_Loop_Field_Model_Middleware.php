<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop\Model_Middleware;

use DI\FactoryInterface;
use Tribe\Project\Block_Middleware\Contracts\Abstract_Model_Middleware;
use Tribe\Project\Block_Middleware\Guards\Block_Model_Middleware_Guard;
use Tribe\Project\Blocks\Contracts\Model;
use Tribe\Project\Blocks\Middleware\Post_Loop\Acf_Field_Fetcher;
use Tribe\Project\Blocks\Middleware\Post_Loop\Field_Middleware\Post_Loop_Field_Middleware;
use Tribe\Project\Blocks\Middleware\Post_Loop\Models\Post_Loop_Model;
use Tribe\Project\Blocks\Middleware\Post_Loop\Post_Loop_Controller;

/**
 * Inject data into a block's model.
 */
class Post_Loop_Field_Model_Middleware extends Abstract_Model_Middleware {

	protected Acf_Field_Fetcher $field_fetcher;
	protected FactoryInterface $container;

	public function __construct(
		Block_Model_Middleware_Guard $guard,
		Acf_Field_Fetcher $field_fetcher,
		FactoryInterface $container
	) {
		parent::__construct( $guard );

		$this->field_fetcher = $field_fetcher;
		$this->container     = $container;
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
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *
	 * @return \Tribe\Project\Blocks\Contracts\Model
	 */
	protected function append_data( Model $model ): Model {
		$fields = $this->field_fetcher->get_fields();

		if ( ! isset( $fields[ Post_Loop_Field_Middleware::NAME ] ) ) {
			return $model;
		}

		$post_loop_model = new Post_Loop_Model( $fields[ Post_Loop_Field_Middleware::NAME ] );

		$controller = $this->container->make( Post_Loop_Controller::class, [
			'model' => $post_loop_model,
		] );

		/**
		 * Allow developers to filter the model index name that is appended to block models.
		 *
		 * @param string                                $index The name of the array index to be added to the model. Default is "posts"
		 * @param \Tribe\Project\Blocks\Contracts\Model $model The model instance this middleware is being run on.
		 */
		$model_index = (string) apply_filters(
			'tribe/project/blocks/middleware/post_loop/model_index',
			Post_Loop_Controller::POSTS,
			$model
		);

		$model->set_data( array_merge( $model->get_data(), [
			$model_index => $controller->get_posts(),
		] ) );

		return $model;
	}

}
