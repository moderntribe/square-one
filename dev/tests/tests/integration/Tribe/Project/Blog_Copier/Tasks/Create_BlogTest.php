<?php

namespace Tribe\Project\Blog_Copier\Tasks;

use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;

class Create_BlogTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @env multisite
	 */
	public function test_creates_blog() {
		/** @var \WP_User $user */
		$user    = $this->factory()->user->create_and_get();
		$config  = new Copy_Configuration( [
			'src'     => 2,
			'address' => 'dest',
			'title'   => 'Copy Destination',
			'files'   => true,
			'notify'  => '',
			'user'    => $user->ID,
		] );
		$post_id = $this->factory()->post->create( [
			'post_type'    => Copy_Manager::POST_TYPE,
			'post_status'  => 'publish',
			'post_content' => \json_encode( $config ),
		] );

		$task = new Create_Blog();
		$task->handle( [
			'post_id' => $post_id,
		] );

		$destination_blog = get_post_meta( $post_id, Copy_Manager::DESTINATION_BLOG, true );

		$this->assertNotEmpty( $destination_blog );

		$this->assertEquals( $config->get_title(), get_blog_option( $destination_blog, 'blogname' ) );
	}

}