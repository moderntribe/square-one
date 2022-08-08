<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use DI\FactoryInterface;
use Tribe\Project\Blocks\Middleware\Post_Loop\Models\Post_Loop_Model;

class Post_Loop_Controller_Factory {

	protected FactoryInterface $container;

	public function __construct( FactoryInterface $container ) {
		$this->container = $container;
	}

	/**
	 * Make a post loop controller instance from ACF Field Data
	 *
	 * @param array $field_data The post loop data as provided from ACF.
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @return \Tribe\Project\Blocks\Middleware\Post_Loop\Post_Loop_Controller
	 */
	public function make( array $field_data ): Post_Loop_Controller {
		return $this->container->make( Post_Loop_Controller::class, [
			'model' => new Post_Loop_Model( $field_data ),
		] );
	}

}
