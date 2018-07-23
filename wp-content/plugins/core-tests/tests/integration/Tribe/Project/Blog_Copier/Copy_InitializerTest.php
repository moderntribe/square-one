<?php

namespace Tribe\Project\Blog_Copier;

use Tribe\Project\Blog_Copier\Tasks\Create_Blog;
use Tribe\Project\Queues\Backends\Mock_Backend;
use Tribe\Project\Queues\Contracts\Queue;

class Copy_InitializerTest extends \Codeception\TestCase\WPTestCase {
	public function test_creates_state_post() {
		/** @var \WP_User $user */
		$user = $this->factory()->user->create_and_get();

		$dest = 'dest';

		$queue = new class ( new Mock_Backend() ) extends Queue {
			public function get_name(): string {
				return 'blog-copier';
			}
		};

		$config = new Copy_Configuration( [
			'src'     => 2,
			'address' => $dest,
			'title'   => 'Destination Blog',
			'files'   => true,
			'notify'  => $user->user_email,
			'user'    => $user->ID,
		] );

		$init          = new Copy_Initializer( $queue );
		$state_post_id = $init->initialize( $config );

		$posts = get_posts( [
			'post_type'   => 'blog-copy',
			'post_status' => 'pending',
			'fields'      => 'ids',
		] );

		$this->assertCount( 1, $posts );

		$this->assertEqualSets( [ $state_post_id ], $posts );

		$state_post = get_post( $state_post_id );
		$this->assertEquals( $config->get_title(), $state_post->post_title );
		$this->assertEquals( json_encode( $config ), $state_post->post_content );

		$this->assertEquals( 1, $queue->count() );
		$job = $queue->reserve();
		$this->assertEquals( Create_Blog::class, $job->get_task_handler() );
	}

}