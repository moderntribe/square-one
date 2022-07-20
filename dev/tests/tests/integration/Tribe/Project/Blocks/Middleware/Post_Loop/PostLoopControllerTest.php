<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Psr\SimpleCache\CacheInterface;
use Tribe\Libs\Field_Models\Models\Image;
use Tribe\Project\Blocks\Middleware\Post_Loop\Models\Post_Loop_Model;
use Tribe\Project\Blocks\Middleware\Post_Loop\Models\Query_Model;
use Tribe\Project\Blocks\Middleware\Post_Loop\Field_Middleware\Post_Loop_Field_Middleware;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Tests\Test_Case;

final class PostLoopControllerTest extends Test_Case {

	private CacheInterface $store;

	public function _setUp() {
		parent::_setUp();

		$this->store = $this->container->make( CacheInterface::class );
	}

	public function _tearDown() {
		parent::_tearDown();

		$this->store->clear();

		$GLOBALS['post'] = null;
	}

	public function test_it_fetches_dynamic_posts(): void {
		$query_model             = new Query_Model();
		$query_model->post_types = [
			Post::NAME,
		];
		$query_model->limit      = 5;

		// WP Unit factory will create these in ascending order.
		$query_model->order = 'ASC';

		$post_loop_model        = new Post_Loop_Model();
		$post_loop_model->query = $query_model;

		$posts = $this->factory()->post->create_many( 5 );
		$posts = array_map( static fn( int $id ) => get_post( $id ), $posts );

		$controller    = new Post_Loop_Controller( $post_loop_model, $this->store );
		$proxied_posts = $controller->get_posts();

		$this->assertSame( count( $posts ), count( $proxied_posts ) );

		for ( $i = 0; $i < count( $posts ); $i ++ ) {
			$this->assertSame( $posts[ $i ]->ID, $proxied_posts[ $i ]->ID );
			$this->assertSame( $posts[ $i ]->post_title, $proxied_posts[ $i ]->post_title );
			$this->assertSame( get_the_title( $posts[ $i ] ), $proxied_posts[ $i ]->cta->link->title );
			$this->assertSame( get_the_permalink( $posts[ $i ] ), $proxied_posts[ $i ]->cta->link->url );
			$this->assertInstanceOf( Image::class, $proxied_posts[ $i ]->image );
		}
	}

	public function test_it_converts_wp_posts_to_proxied_posts(): void {
		$query_model             = new Query_Model();
		$query_model->post_types = [
			Post::NAME,
		];

		$post_loop_model        = new Post_Loop_Model();
		$post_loop_model->query = $query_model;

		$posts = $this->factory()->post->create_many( 5 );
		$posts = array_map( static fn( int $id ) => get_post( $id ), $posts );

		$controller    = new Post_Loop_Controller( $post_loop_model, $this->store );
		$proxied_posts = $controller->proxy_posts( $posts );

		$this->assertSame( count( $posts ), count( $proxied_posts ) );

		for ( $i = 0; $i < count( $posts ); $i ++ ) {
			$this->assertSame( $posts[ $i ]->ID, $proxied_posts[ $i ]->ID );
			$this->assertSame( $posts[ $i ]->post_title, $proxied_posts[ $i ]->post_title );
			$this->assertSame( get_the_title( $posts[ $i ] ), $proxied_posts[ $i ]->cta->link->title );
			$this->assertSame( get_the_permalink( $posts[ $i ] ), $proxied_posts[ $i ]->cta->link->url );
			$this->assertInstanceOf( Image::class, $proxied_posts[ $i ]->image );
		}
	}

	public function test_it_fetches_proxied_faux_manual_posts(): void {
		$post_loop_model             = new Post_Loop_Model();
		$post_loop_model->query_type = Post_Loop_Field_Middleware::QUERY_TYPE_MANUAL;
		$cat_id_2                    = $this->factory()->category->create();
		$cat_id_3                    = $this->factory()->category->create();

		// Build the repeater data as ACF would provide it.
		$posts = [
			[
				Post_Loop_Field_Middleware::MANUAL_POST_AUTHOR => 1,
				Post_Loop_Field_Middleware::MANUAL_TOGGLE      => true,
				Post_Loop_Field_Middleware::MANUAL_POST        => false,
				Post_Loop_Field_Middleware::MANUAL_TITLE       => 'Test post 1',
				Post_Loop_Field_Middleware::MANUAL_EXCERPT     => 'Test post 1 excerpt',
				Post_Loop_Field_Middleware::MANUAL_POST_DATE   => current_time( 'mysql' ),
			],
			[
				Post_Loop_Field_Middleware::MANUAL_POST_AUTHOR   => 1,
				Post_Loop_Field_Middleware::MANUAL_TOGGLE        => true,
				Post_Loop_Field_Middleware::MANUAL_POST          => false,
				Post_Loop_Field_Middleware::MANUAL_TITLE         => 'Test post 2',
				Post_Loop_Field_Middleware::MANUAL_EXCERPT       => 'Test post 2 excerpt',
				Post_Loop_Field_Middleware::MANUAL_POST_DATE     => current_time( 'mysql' ),
				Post_Loop_Field_Middleware::MANUAL_POST_CATEGORY => $cat_id_2,
			],
			[
				Post_Loop_Field_Middleware::MANUAL_POST_AUTHOR   => 1,
				Post_Loop_Field_Middleware::MANUAL_TOGGLE        => true,
				Post_Loop_Field_Middleware::MANUAL_POST          => false,
				Post_Loop_Field_Middleware::MANUAL_TITLE         => 'Test post 3',
				Post_Loop_Field_Middleware::MANUAL_EXCERPT       => 'Test post 3 excerpt',
				Post_Loop_Field_Middleware::MANUAL_POST_DATE     => current_time( 'mysql' ),
				Post_Loop_Field_Middleware::MANUAL_POST_CATEGORY => $cat_id_3,
			],
		];

		$post_loop_model->manual_posts = $posts;

		$controller    = new Post_Loop_Controller( $post_loop_model, $this->store );
		$proxied_posts = $controller->get_posts();

		$this->assertSame( count( $posts ), count( $proxied_posts ) );

		for ( $i = 0; $i < count( $posts ); $i ++ ) {
			$this->assertSame( sprintf( 'Test post %d excerpt', $i+1 ), $proxied_posts[$i]->post_excerpt );
			$this->assertSame( $posts[$i][ Post_Loop_Field_Middleware::MANUAL_POST_DATE ], $proxied_posts[$i]->post_date );
			$this->assertSame( get_gmt_from_date( $posts[$i][ Post_Loop_Field_Middleware::MANUAL_POST_DATE ] ), $proxied_posts[$i]->post_date_gmt );
			$this->assertSame( $posts[$i][ Post_Loop_Field_Middleware::MANUAL_POST_AUTHOR ], $proxied_posts[$i]->post_author );

			// Faux posts have negative ID's, so $proxied_post->post() must be used when working with WordPress template functions.
			// This is necessary to avoid a database collision with a real post of the same ID.
			$this->assertLessThan( 0, $proxied_posts[$i]->ID );
			$this->assertSame( $posts[$i][ Post_Loop_Field_Middleware::MANUAL_POST_DATE ], get_the_date( 'Y-m-d H:i:s', $proxied_posts[$i]->post() )  );

			// Due to how WordPress checks the cache, we can even pass our negative ID's to core functions
			$this->assertSame( sprintf( 'Test post %d', $i+1 ), get_the_title( $proxied_posts[$i]->ID ) );

			// But, we can get it from the actual post object due to `filter => 'raw'`
			$this->assertSame( sprintf( 'Test post %d', $i+1 ), get_the_title( $proxied_posts[$i]->post() ) );

			// Same goes for any template/post function...always use the proxied WP_Post object for these.
			$this->assertSame( sprintf( 'Test post %d excerpt', $i+1 ), get_the_excerpt( $proxied_posts[$i]->ID ) );
			$this->assertSame( sprintf( 'Test post %d excerpt', $i+1 ), get_the_excerpt( $proxied_posts[$i]->post() ) );

			$this->assertSame( 1, get_post_field( 'post_author', $proxied_posts[$i]->ID ) );
			$this->assertSame( 1, get_post_field( 'post_author', $proxied_posts[$i]->post() ) );

			// We build post slugs as `p-$ID`, faux post IDs are negative, so convert them to a positive int like the controller does.
			$this->assertSame( sprintf( 'p-%d', abs( $proxied_posts[$i]->ID ) ), $proxied_posts[$i]->post_name );
		}

		// Test raw category assignments
		$this->assertSame( 1, $proxied_posts[0]->post_category[0] );
		$this->assertSame( $cat_id_2, $proxied_posts[1]->post_category[0] );
		$this->assertSame( $cat_id_3, $proxied_posts[2]->post_category[0] );

		// Set the current post global and then call get_the_category() without a post ID to fetch the correct category.
		$GLOBALS['post'] = $proxied_posts[1]->post();
		$this->assertSame( $cat_id_2, get_the_category()[0]->term_id );

		$GLOBALS['post'] = $proxied_posts[2]->post();
		$this->assertSame( $cat_id_3, get_the_category()[0]->term_id );

	}

}
