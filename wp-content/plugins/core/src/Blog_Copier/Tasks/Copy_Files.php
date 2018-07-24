<?php


namespace Tribe\Project\Blog_Copier\Tasks;


use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;
use Tribe\Project\Queues\Contracts\Task;

class Copy_Files implements Task {

	public function handle( array $args ): bool {
		$post_id = empty( $args[ 'post_id' ] ) ? 0 : absint( $args[ 'post_id' ] );

		$data        = \json_decode( get_post_field( 'post_content', $post_id, 'raw' ), true );
		$config      = new Copy_Configuration( $data );
		$destination = get_post_meta( $post_id, Copy_Manager::DESTINATION_BLOG, true );

		$src = $config->get_src();

		if ( empty( $src ) || empty( $destination ) ) {
			$error = new \WP_Error( 'missing_blog', __( 'Source and destination blogs both must exist to copy files.', 'tribe' ) );
			do_action( Copy_Manager::TASK_ERROR_ACTION, static::class, $args, $error );

			return true;
		}

		if ( $config->get_files() ) {
			$this->copy_files( $src, $destination );
		}

		do_action( Copy_Manager::TASK_COMPLETE_ACTION, static::class, $args );

		return true;
	}

	private function copy_files( $src, $destination ) {
		// trigger an action so that we can have various strategies depending on the hosting environment
		do_action( 'tribe/project/copy-blog/copy-files', $src, $destination );
	}

}