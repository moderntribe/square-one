<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

class Post_Loop_Repository {

	protected Post_Loop_Controller_Factory $factory;

	public function __construct( Post_Loop_Controller_Factory $factory ) {
		$this->factory = $factory;
	}

	/**
	 * Fetch posts, generally from inside a Block Model based on the field a user defined in the Block_Config.
	 *
	 * @param array $field_data The post loop data as provided from ACF.
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *
	 * @return \Tribe\Project\Blocks\Middleware\Post_Loop\Post_Proxy[]
	 */
	public function get_posts( array $field_data ): array {
		return $this->factory->make( $field_data )->get_posts();
	}

}
