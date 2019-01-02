<?php


namespace Tribe\Project\Blog_Copier\Tasks;


use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;
use Tribe\Project\Queues\Contracts\Task;

/**
 * Class Replace_Options
 *
 * Fix all options that have a prefix derived from the source blog (e.g., user roles)
 */
class Replace_Options implements Task {

	public function handle( array $args ): bool {
		$post_id = empty( $args[ 'post_id' ] ) ? 0 : absint( $args[ 'post_id' ] );

		$data        = \json_decode( get_post_field( 'post_content', $post_id, 'raw' ), true );
		$config      = new Copy_Configuration( $data );
		$destination = get_post_meta( $post_id, Copy_Manager::DESTINATION_BLOG, true );

		$src = $config->get_src();

		if ( empty( $src ) || empty( $destination ) ) {
			$error = new \WP_Error( 'missing_blog', __( 'Source and destination blogs both must exist to replace options.', 'tribe' ) );
			do_action( Copy_Manager::TASK_ERROR_ACTION, static::class, $args, $error );

			return true;
		}

		$this->replace_options( $src, $destination );

		do_action( Copy_Manager::TASK_COMPLETE_ACTION, static::class, $args );

		return true;
	}

	private function replace_options( $src, $destination ) {
		/** @var \wpdb $wpdb */
		global $wpdb;

		$src_prefix    = $wpdb->get_blog_prefix( $src );
		$dest_prefix   = $wpdb->get_blog_prefix( $destination );
		$prefix_length = strlen( $src_prefix );

		switch_to_blog( $destination );
		$query   = $wpdb->prepare( "SELECT option_id, option_name FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like( "$src_prefix" ) . '%' );
		$options = $wpdb->get_results( $query );
		if ( $options ) {
			foreach ( $options as $option ) {
				$raw_option_name = substr( $option->option_name, $prefix_length );
				$wpdb->update( $wpdb->options, [ 'option_name' => $dest_prefix . $raw_option_name ], [ 'option_id' => $option->option_id ] );
			}

			// caches will be incorrect after direct DB copies
			wp_cache_delete( 'notoptions', 'options' );
			wp_cache_delete( 'alloptions', 'options' );
		}
		restore_current_blog();
	}

}