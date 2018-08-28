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

		$this->replace_urls( $src, $destination, $config->get_files() );

		do_action( Copy_Manager::TASK_COMPLETE_ACTION, static::class, $args );

		return true;
	}

	/**
	 * @param int  $src     ID of the source blog
	 * @param int  $dest    ID of the destination blog
	 * @param bool $uploads Whether to alter upload paths
	 *
	 * @return void
	 */
	private function replace_urls( $src, $dest, $uploads = false ) {
		/** @var \wpdb $wpdb */
		global $wpdb;
		$dest_prefix = $wpdb->get_blog_prefix( $dest );
		switch_to_blog( $src );
		$src_url         = get_option( 'siteurl' );
		$src_uploads_url = wp_upload_dir( null, false, true )[ 'baseurl' ];
		restore_current_blog();

		switch_to_blog( $dest );
		$dest_url         = get_option( 'siteurl' );
		$dest_uploads_url = wp_upload_dir( null, false, true )[ 'baseurl' ];
		restore_current_blog();

		$map = [];
		if ( $uploads ) {
			$map[ parse_url( $src_uploads_url, PHP_URL_PATH ) ] = parse_url( $dest_uploads_url, PHP_URL_PATH );
		}
		$map[ set_url_scheme( $src_url, 'http' ) ] = set_url_scheme( $dest_url, 'http' );
		$map[ set_url_scheme( $src_url, 'https' ) ] = set_url_scheme( $dest_url, 'https' );

		/**
		 * Filter the mapping of source URLs to destination URLs
		 *
		 * @param array $map     Source URLs mapped to their destination URLs
		 * @param int   $src     The source blog ID
		 * @param int   $dest    The destination blog ID
		 * @param bool  $uploads Whether the uploads path should be mapped
		 */
		$map = apply_filters( 'tribe/project/copy-blog/replace-urls/map', $map, $src, $dest, $uploads );

		foreach ( $map as $from => $to ) {
			// replace URLs in post_content
			if ( empty( $from ) || empty( $to ) ) {
				continue;
			}
			$wpdb->query( $wpdb->prepare( "UPDATE {$dest_prefix}posts SET post_content = REPLACE(post_content, %s, %s)", $from, $to ) );

			// replace json-encoded URLs in post_content_filtered
			$json_from = \trim( \json_encode( $from ), '"' );
			$json_to      = \trim( \json_encode( $to ), '"' );
			if ( ! empty( $json_from ) && ! empty( $json_to ) ) {
				$wpdb->query( $wpdb->prepare( "UPDATE {$dest_prefix}posts SET post_content_filtered = REPLACE(post_content_filtered, %s, %s)", $json_from, $json_to ) );
			}
		}
	}

}