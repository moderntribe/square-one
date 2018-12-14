<?php


namespace Tribe\Project\Blog_Copier\Tasks;


use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;
use Tribe\Project\Blog_Copier\Strategies\Recursive_File_Copy;
use Tribe\Project\Blog_Copier\Strategies\Shell_File_Copy;
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
		/**
		 * Triggers a copy of files
		 *
		 * Depending on the hosting environment, a different strategy for handling
		 * the action may be necessary
		 *
		 * @param string $source      The source directory
		 * @param string $destination The destination directory
		 *
		 * @see Shell_File_Copy
		 * @see Recursive_File_Copy
		 */
		do_action( 'tribe/project/copy-blog/copy-files', $src, $destination );
	}

}