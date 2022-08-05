<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Tribe\Libs\Field_Models\Models\Post_Proxy;
use Tribe\Tests\Test_Case;

final class PostLoopRepositoryTest extends Test_Case {

	public function test_it_fetches_dynamic_posts_from_the_repository(): void {
		$this->factory()->post->create_many( 10 );

		$repository = $this->container->make( Post_Loop_Repository::class );

		// Mock of the ACF field data coming in from a Block Model
		$field_data = [
			'query_type'   => 'query_type_auto',
			'query'        =>
				[
					'limit'      => '30',
					'post_types' =>
						[
							0 => 'post',
						],
					'order'      => 'desc',
					'order_by'   => 'date',
				],
			'taxonomies'   =>
				[
					'category' => false,
					'post_tag' => false,
				],
			'manual_posts' => false,
		];

		$posts = $repository->get_posts( $field_data );

		$this->assertCount( 10, $posts );
		$this->assertInstanceOf( Post_Proxy::class, reset( $posts ) );
	}

}
