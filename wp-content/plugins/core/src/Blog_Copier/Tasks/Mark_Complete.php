<?php


namespace Tribe\Project\Blog_Copier\Tasks;

use Tribe\Project\Blog_Copier\Copy_Manager;
use Tribe\Project\Queues\Contracts\Task;

class Mark_Complete implements Task {

	public function handle( array $args ): bool  {
		$post_id = empty( $args['post_id'] ) ? 0 : absint( $args['post_id'] );
		wp_publish_post( $post_id );
		do_action( Copy_Manager::TASK_COMPLETE_ACTION, static::class, $args );

		return true;
	}

}
