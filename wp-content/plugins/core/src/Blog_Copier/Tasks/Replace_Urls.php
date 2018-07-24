<?php


namespace Tribe\Project\Blog_Copier\Tasks;


use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;
use Tribe\Project\Queues\Contracts\Task;

class Replace_Urls implements Task {

	public function handle( array $args ): bool {
		$post_id = empty( $args[ 'post_id' ] ) ? 0 : absint( $args[ 'post_id' ] );

		$data        = \json_decode( get_post_field( 'post_content', $post_id, 'raw' ), true );
		$config      = new Copy_Configuration( $data );
		$destination = get_post_meta( $post_id, Copy_Manager::DESTINATION_BLOG, true );

		$src = $config->get_src();

		if ( empty( $src ) || empty( $destination ) ) {
			$error = new \WP_Error( 'missing_blog', __( 'Source and destination blogs both must exist to update URLs.', 'tribe' ) );
			do_action( Copy_Manager::TASK_ERROR_ACTION, static::class, $args, $error );

			return true;
		}

		if ( $config->get_files() ) {
			$this->replace_urls( $src, $destination );
		}

		do_action( Copy_Manager::TASK_COMPLETE_ACTION, static::class, $args );

		return true;
	}

	private function replace_urls( $src, $dest ) {
		/** @var \wpdb $wpdb */
		global $wpdb;
		$dest_prefix   = $wpdb->get_blog_prefix( $dest );
		$src_url       = get_blog_option( $src, 'siteurl' );
		$dest_url      = get_blog_option( $dest, 'siteurl' );
		$json_src_url  = \trim( \json_encode( $src_url ), '"' );
		$json_dest_url = \trim( \json_encode( $dest_url ), '"' );

		$query = $wpdb->prepare( "UPDATE {$dest_prefix}posts SET post_content = REPLACE(post_content, '%s', '%s')", $src_url, $dest_url );
		$wpdb->query( $query );
		$query = $wpdb->prepare( "UPDATE {$dest_prefix}posts SET post_content_filtered = REPLACE(post_content_filtered, '%s', '%s')", $json_src_url, $json_dest_url );
		$wpdb->query( $query );
	}

}