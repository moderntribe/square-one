<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Psr\SimpleCache\CacheInterface;
use Tribe\Tests\Test_Case;
use WP_Post;

final class PostCacheManagerTest extends Test_Case {

	private CacheInterface $store;
	private Post_Cache_Manager $post_cache_manager;

	public function _setUp() {
		parent::_setUp();

		$this->store              = $this->container->make( CacheInterface::class );
		$this->post_cache_manager = $this->container->make( Post_Cache_Manager::class, [
			'store' => $this->store,
		] );
	}

	public function _tearDown() {
		parent::_tearDown();

		$this->store->clear();
	}

	public function test_it_adds_posts_to_the_cache_and_store(): void {
		$post_proxy = new Post_Proxy( [
			'ID' => - 99,
		] );

		$this->assertFalse( $this->store->has( (string) $post_proxy->ID ) );
		$this->assertEmpty( wp_cache_get( $post_proxy->ID, 'posts' ) );

		$this->post_cache_manager->add_post( $post_proxy );

		$cached_post = wp_cache_get( $post_proxy->ID, 'posts' );

		$this->assertInstanceOf( WP_Post::class, $cached_post );
		$this->assertSame( - 99, $cached_post->ID );
		$this->assertTrue( $this->store->has( (string) $post_proxy->ID ) );
		$this->assertEquals( $cached_post, $post_proxy->post() );
	}

	public function test_it_flushes_faux_post_term_cache(): void {
		$cat_id = $this->factory()->category->create();

		$post_proxy = new Post_Proxy( [
			'ID'            => - 998,
			'post_category' => [
				$cat_id,
			],
		] );

		$this->assertFalse( wp_cache_get( $post_proxy->ID, 'category_relationships' ) );

		wp_cache_add( $post_proxy->ID, [ $cat_id ], 'category_relationships' );

		$this->assertNotEmpty( wp_cache_get( $post_proxy->ID, 'category_relationships' ) );

		$this->post_cache_manager->flush_term_relationship( $post_proxy->ID );

		$this->assertFalse( wp_cache_get( $post_proxy->ID, 'category_relationships' ) );
	}

}
