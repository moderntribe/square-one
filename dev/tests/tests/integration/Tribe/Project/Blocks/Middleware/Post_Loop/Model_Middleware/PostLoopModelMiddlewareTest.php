<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop\Field_Middleware;

use Mockery;
use Tribe\Project\Block_Middleware\Guards\Block_Model_Middleware_Guard;
use Tribe\Project\Blocks\Contracts\Model;
use Tribe\Project\Blocks\Middleware\Post_Loop\Acf_Field_Fetcher;
use Tribe\Project\Blocks\Middleware\Post_Loop\Model_Middleware\Post_Loop_Field_Model_Middleware;
use Tribe\Project\Blocks\Middleware\Post_Loop\Models\Post_Loop_Model;
use Tribe\Project\Blocks\Middleware\Post_Loop\Models\Query_Model;
use Tribe\Project\Blocks\Middleware\Post_Loop\Post_Loop_Controller;
use Tribe\Project\Blocks\Middleware\Post_Loop\Post_Proxy;
use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Tests\Test_Case;
use WP_Post;

final class PostLoopModelMiddlewareTest extends Test_Case {

	/**
	 * @var \Tribe\Project\Blocks\Middleware\Post_Loop\Acf_Field_Fetcher|\Mockery\MockInterface
	 */
	private $field_fetcher;

	/**
	 * @var Block_Model_Middleware_Guard|\Mockery\MockInterface
	 */
	private $guard;

	private Post_Loop_Field_Model_Middleware $middleware;

	public function _setUp() {
		parent::_setUp();

		$this->field_fetcher = Mockery::mock( Acf_Field_Fetcher::class );
		$this->guard         = Mockery::mock( Block_Model_Middleware_Guard::class );

		$this->middleware = $this->container->make( Post_Loop_Field_Model_Middleware::class, [
			'guard'         => $this->guard,
			'field_fetcher' => $this->field_fetcher,
		] );
	}

	public function test_it_adds_dynamic_posts_to_the_block_model(): void {
		// Create the posts that the Post Loop Field will dynamically query
		$posts = $this->factory()->post->create_many( 5 );
		$posts = array_map( static fn( int $id ) => get_post( $id ), $posts );

		$model = new class( [
			'name' => 'acf/testblock',
		] ) extends Base_Model {

			protected function init_data(): array {
				return [];
			}

		};

		// Build the ACF repeater data using our models just because it's way easier.
		$query             = new Query_Model();
		$query->post_types = [
			Post::NAME,
		];
		$query->limit      = 5;
		$query->order      = Post_Loop_Field_Middleware::OPTION_ASC;

		$post_loop_model        = new Post_Loop_Model();
		$post_loop_model->query = $query;

		// Mock ACF's return of get_fields(), which is using globals and too difficult to test.
		$this->field_fetcher->shouldReceive( 'get_fields' )->once()->andReturn( [
			Post_Loop_Field_Middleware::NAME => $post_loop_model->toArray(),
		] );

		$this->guard->shouldReceive( 'allowed' )->once()->andReturnTrue();

		$closure = static function ( Model $model ): Model {
			return $model;
		};

		$model = $this->middleware->set_data( $model, $closure );

		// We should now have a "posts" index in the model data, which contains an array of Post_Proxy
		// objects using the test posts we created above. This could now be passed to a controller
		// that has a $posts property.
		$model_posts = $model->get_data()[ Post_Loop_Controller::POSTS ];

		$this->assertSame( count( $posts ), count( $model_posts ) );

		// Compare data from the original posts to our posts from the post loop field.
		for ( $i = 0; $i < count( $posts ); $i ++ ) {
			$this->assertInstanceOf( WP_Post::class, $posts[ $i ] );
			$this->assertInstanceOf( Post_Proxy::class, $model_posts[ $i ] );
			$this->assertSame( $posts[ $i ]->ID, $model_posts[ $i ]->ID );
			$this->assertSame( $posts[ $i ]->post_title, $model_posts[ $i ]->post_title );
		}

	}

	public function test_it_adds_faux_manual_posts_to_the_block_model(): void {
		$model = new class( [
			'name' => 'acf/testblock',
		] ) extends Base_Model {

			protected function init_data(): array {
				return [];
			}

		};

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
				Post_Loop_Field_Middleware::MANUAL_POST_AUTHOR => 1,
				Post_Loop_Field_Middleware::MANUAL_TOGGLE      => true,
				Post_Loop_Field_Middleware::MANUAL_POST        => false,
				Post_Loop_Field_Middleware::MANUAL_TITLE       => 'Test post 2',
				Post_Loop_Field_Middleware::MANUAL_EXCERPT     => 'Test post 2 excerpt',
				Post_Loop_Field_Middleware::MANUAL_POST_DATE   => current_time( 'mysql' ),
			],
			[
				Post_Loop_Field_Middleware::MANUAL_POST_AUTHOR => 1,
				Post_Loop_Field_Middleware::MANUAL_TOGGLE      => true,
				Post_Loop_Field_Middleware::MANUAL_POST        => false,
				Post_Loop_Field_Middleware::MANUAL_TITLE       => 'Test post 3',
				Post_Loop_Field_Middleware::MANUAL_EXCERPT     => 'Test post 3 excerpt',
				Post_Loop_Field_Middleware::MANUAL_POST_DATE   => current_time( 'mysql' ),
			],
		];

		$post_loop_model               = new Post_Loop_Model();
		$post_loop_model->manual_posts = $posts;
		$post_loop_model->query_type   = Post_Loop_Field_Middleware::QUERY_TYPE_MANUAL;

		// Mock ACF's return of get_fields(), which is using globals and too difficult to test.
		$this->field_fetcher->shouldReceive( 'get_fields' )->once()->andReturn( [
			Post_Loop_Field_Middleware::NAME => $post_loop_model->toArray(),
		] );

		$this->guard->shouldReceive( 'allowed' )->once()->andReturnTrue();

		$closure = static function ( Model $model ): Model {
			return $model;
		};

		$model = $this->middleware->set_data( $model, $closure );

		// We should now have a "posts" index in the model data, which contains an array of Post_Proxy
		// objects which aren't actually real posts in the database, but Post_Proxy objects with a negative post ID.
		$model_posts = $model->get_data()[ Post_Loop_Controller::POSTS ];

		$this->assertSame( count( $posts ), count( $model_posts ) );

		// Compare data from the original posts to our posts from the post loop field.
		for ( $i = 0; $i < count( $posts ); $i ++ ) {
			$this->assertInstanceOf( Post_Proxy::class, $model_posts[ $i ] );
			$this->assertLessThan( 0, $model_posts[ $i ]->post()->ID );
			$this->assertSame( $posts[ $i ][ Post_Loop_Field_Middleware::MANUAL_TITLE ], $model_posts[ $i ]->post_title );
			$this->assertSame( $posts[ $i ][ Post_Loop_Field_Middleware::MANUAL_EXCERPT ], $model_posts[ $i ]->post_excerpt );
			$this->assertSame( $posts[ $i ][ Post_Loop_Field_Middleware::MANUAL_POST_DATE ], $model_posts[ $i ]->post_date );
		}

	}

}
