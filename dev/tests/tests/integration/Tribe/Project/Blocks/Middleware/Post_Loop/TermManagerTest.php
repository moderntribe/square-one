<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Psr\SimpleCache\CacheInterface;
use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Tests\Test_Case;

final class TermManagerTest extends Test_Case {

	private CacheInterface $store;

	public function _setUp() {
		parent::_setUp();

		$this->store = $this->container->make( CacheInterface::class );
	}

	public function _tearDown(): void {
		parent::_tearDown();

		$this->store->clear();
	}

	public function test_it_finds_a_category_in_a_faux_post_proxy(): void {
		$cat_id  = $this->factory()->category->create();
		$post_id = - 9999;

		$post = new Post_Proxy( [
			'ID'             => $post_id,
			'filter'         => 'raw',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_name'      => 'p-9999',
			'post_title'     => 'faux post',
			'post_type'      => 'post',
			'post_date'      => current_time( 'mysql' ),
			'post_category'  => [
				$cat_id,
			],
		] );

		$this->store->set( (string) $post->ID, $post );

		$term_manager = $this->container->make( Term_Manager::class, [
			'allowed_taxonomies' => [
				Category::NAME,
			],
		] );

		$args = [
			'taxonomy'   => [
				Category::NAME,
			],
			'object_ids' => [
				$post_id,
			],
		];

		$updated_terms = $term_manager->get_terms( [], $args );

		$this->assertCount( 1, $updated_terms );

		$this->assertSame( $cat_id, reset( $updated_terms )->term_id );
	}

	public function test_it_finds_a_category_in_an_overridden_manual_post(): void {
		$assigned_cat_id = $this->factory()->category->create();
		$new_cat_id      = $this->factory()->category->create();
		$post            = $this->factory()->post->create_and_get( [
			'post_category' => [
				$assigned_cat_id,
			],
		] );

		$this->assertSame( $assigned_cat_id, get_the_category( $post->ID )[0]->term_id );

		$post_data                  = $post->to_array();
		$post_data['post_category'] = [
			$new_cat_id,
		];

		$post_proxy = new Post_Proxy( $post_data );

		$this->store->set( (string) $post_proxy->ID, $post_proxy );

		$term_manager = $this->container->make( Term_Manager::class, [
			'allowed_taxonomies' => [
				Category::NAME,
			],
		] );

		$terms = [
			get_term( $assigned_cat_id ),
		];

		$args = [
			'taxonomy'   => [
				Category::NAME,
			],
			'object_ids' => [
				$post->ID,
			],
		];

		$appended_terms = $term_manager->get_terms( $terms, $args );

		$this->assertCount( 1, $appended_terms );

		$this->assertSame( $new_cat_id, reset( $appended_terms )->term_id );
	}

	public function test_it_passes_through_existing_manual_post_categories(): void {
		$cat_id_1 = $this->factory()->category->create();
		$cat_id_2 = $this->factory()->category->create();
		$post     = $this->factory()->post->create_and_get( [
			'post_category' => [
				$cat_id_1,
				$cat_id_2,
			],
		] );

		$this->assertSame( $cat_id_1, get_the_category( $post->ID )[0]->term_id );
		$this->assertSame( $cat_id_2, get_the_category( $post->ID )[1]->term_id );

		$post_data = $post->to_array();

		$post_proxy = new Post_Proxy( $post_data );

		$this->store->set( (string) $post_proxy->ID, $post_proxy );

		$term_manager = $this->container->make( Term_Manager::class, [
			'allowed_taxonomies' => [
				Category::NAME,
			],
		] );

		$terms = [
			get_term( $cat_id_1 ),
			get_term( $cat_id_2 ),
		];

		$args = [
			'taxonomy'   => [
				Category::NAME,
			],
			'object_ids' => [
				$post->ID,
			],
		];

		$updated_terms = $term_manager->get_terms( $terms, $args );

		$this->assertCount( 2, $updated_terms );

		$this->assertSame( $cat_id_1, reset( $updated_terms )->term_id );
		$this->assertSame( $cat_id_2, last( $updated_terms )->term_id );
	}

	public function test_it_adjusts_the_position_of_existing_manual_post_categories(): void {
		$cat_id_1 = $this->factory()->category->create();
		$cat_id_2 = $this->factory()->category->create();
		$post     = $this->factory()->post->create_and_get( [
			'post_category' => [
				$cat_id_1,
				$cat_id_2,
			],
		] );

		$this->assertSame( $cat_id_1, get_the_category( $post->ID )[0]->term_id );
		$this->assertSame( $cat_id_2, get_the_category( $post->ID )[1]->term_id );

		$post_data = $post->to_array();

		$post_proxy = new Post_Proxy( $post_data );

		$this->store->set( (string) $post_proxy->ID, $post_proxy );

		$term_manager = $this->container->make( Term_Manager::class, [
			'allowed_taxonomies' => [
				Category::NAME,
			],
		] );

		$terms = [
			get_term( $cat_id_2 ),
			get_term( $cat_id_1 ),
		];

		$args = [
			'taxonomy'   => [
				Category::NAME,
			],
			'object_ids' => [
				$post->ID,
			],
		];

		$updated_terms = $term_manager->get_terms( $terms, $args );

		$this->assertCount( 2, $updated_terms );

		$this->assertSame( $cat_id_1, reset( $updated_terms )->term_id );
		$this->assertSame( $cat_id_2, last( $updated_terms )->term_id );
	}

	public function test_it_skips_posts_that_are_not_in_the_store(): void {
		$cat_id_1 = $this->factory()->category->create();
		$cat_id_2 = $this->factory()->category->create();
		$post     = $this->factory()->post->create_and_get( [
			'post_category' => [
				$cat_id_1,
				$cat_id_2,
			],
		] );

		$this->assertSame( $cat_id_1, get_the_category( $post->ID )[0]->term_id );
		$this->assertSame( $cat_id_2, get_the_category( $post->ID )[1]->term_id );

		$term_manager = $this->container->make( Term_Manager::class, [
			'allowed_taxonomies' => [
				Category::NAME,
			],
		] );

		$terms = [
			get_term( $cat_id_1 ),
			get_term( $cat_id_2 ),
		];

		$args = [
			'taxonomy'   => [
				Category::NAME,
			],
			'object_ids' => [
				$post->ID,
			],
		];

		$updated_terms = $term_manager->get_terms( $terms, $args );

		$this->assertCount( 2, $updated_terms );

		$this->assertSame( $cat_id_1, reset( $updated_terms )->term_id );
		$this->assertSame( $cat_id_2, last( $updated_terms )->term_id );
	}


	public function test_it_skips_non_category_taxonomies(): void {
		$tag_id_1 = $this->factory()->tag->create();
		$tag_id_2 = $this->factory()->tag->create();
		$post     = $this->factory()->post->create_and_get( [
			'post_category' => [
				$tag_id_1,
				$tag_id_2,
			],
		] );

		$term_manager = $this->container->make( Term_Manager::class, [
			'allowed_taxonomies' => [
				Category::NAME,
			],
		] );

		$terms = [
			get_term( $tag_id_1 ),
			get_term( $tag_id_2 ),
		];

		$args = [
			'taxonomy'   => [
				Category::NAME,
			],
			'object_ids' => [
				$post->ID,
			],
		];

		$updated_terms = $term_manager->get_terms( $terms, $args );
		$this->assertCount( 2, $updated_terms );

		$this->assertSame( $tag_id_1, reset( $updated_terms )->term_id );
		$this->assertSame( $tag_id_2, last( $updated_terms )->term_id );
	}

}
