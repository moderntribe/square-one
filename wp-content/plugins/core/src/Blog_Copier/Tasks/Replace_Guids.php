<?php


namespace Tribe\Project\Blog_Copier\Tasks;

use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;
use Tribe\Project\Queues\Contracts\Task;

class Replace_Guids implements Task {

	public function handle( array $args ): bool  {
		$post_id = empty( $args['post_id'] ) ? 0 : absint( $args['post_id'] );

		$data        = \json_decode( get_post_field( 'post_content', $post_id, 'raw' ), true );
		$config      = new Copy_Configuration( $data );
		$destination = get_post_meta( $post_id, Copy_Manager::DESTINATION_BLOG, true );

		$src = $config->get_src();

		if ( empty( $src ) || empty( $destination ) ) {
			$error = new \WP_Error( 'missing_blog', __( 'Source and destination blogs both must exist to replace guids.', 'tribe' ) );
			do_action( Copy_Manager::TASK_ERROR_ACTION, static::class, $args, $error );

			return true;
		}

		$this->replace_guids( $src, $destination );

		do_action( Copy_Manager::TASK_COMPLETE_ACTION, static::class, $args );

		return true;
	}

	private function replace_guids( $src, $destination ) {
		/** @var \wpdb $wpdb */
		global $wpdb;
		$dest_prefix = $wpdb->get_blog_prefix( $destination );
		$src_url     = get_blog_option( $src, 'siteurl' );
		$dest_url    = get_blog_option( $destination, 'siteurl' );
		$query       = $wpdb->prepare( "UPDATE {$dest_prefix}posts SET guid = REPLACE(guid, '%s', '%s')", $src_url, $dest_url );
		$wpdb->query( $query );
	}

}
