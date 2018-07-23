<?php


namespace Tribe\Project\Blog_Copier;


use Tribe\Project\Blog_Copier\Tasks\Create_Blog;
use Tribe\Project\Queues\Contracts\Queue;

class Copy_Initializer {
	/**
	 * @var Queue
	 */
	private $queue;

	public function __construct( Queue $queue ) {
		$this->queue = $queue;
	}

	/**
	 * @param Copy_Configuration $configuration
	 *
	 * @return int
	 * @action tribe/project/copy-blog/copy
	 */
	public function initialize( Copy_Configuration $configuration ) {
		$post_id = $this->create_state_post( $configuration );
		$this->create_task( $post_id );
		return $post_id;
	}

	private function create_state_post( Copy_Configuration $configuration ) {
		$post_id = wp_insert_post( [
			'post_type'    => 'blog-copy',
			'post_status'  => 'pending',
			'post_title'   => $configuration->get_title(),
			'post_content' => json_encode( $configuration ),
		] );

		return $post_id;
	}

	private function create_task( $post_id ) {
		$this->queue->dispatch( Create_Blog::class, [ 'post_id' => $post_id ] );
	}
}