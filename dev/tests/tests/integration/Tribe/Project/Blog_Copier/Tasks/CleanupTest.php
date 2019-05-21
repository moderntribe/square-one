<?php

namespace Tribe\Project\Blog_Copier\Tasks;

use Tribe\Project\Blog_Copier\Copy_Manager;
use Tribe\Tests\SquareOneTestCase;

class CleanupTest extends SquareOneTestCase {

	public function test_deletes_post() {
		$post_id = $this->factory()->post->create( [
			'post_type'   => Copy_Manager::POST_TYPE,
			'post_status' => 'publish',
		] );

		$this->assertCount( 1, get_posts( [
			'post_type' => Copy_Manager::POST_TYPE,
			'p'         => $post_id,
		] ) );

		$cleanup = new Cleanup();
		$cleanup->handle( [
			'post_id' => $post_id,
		] );

		$this->assertCount( 0, get_posts( [
			'post_type' => Copy_Manager::POST_TYPE,
			'p'         => $post_id,
		] ) );
	}

}