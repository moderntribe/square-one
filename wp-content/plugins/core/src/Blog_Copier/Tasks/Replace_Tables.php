<?php


namespace Tribe\Project\Blog_Copier\Tasks;


use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;
use Tribe\Project\Queues\Contracts\Task;

/**
 * Class Replace_Tables
 *
 * Ensure that all tables on the destination blog are empty and match the
 * schema of the source blog
 */
class Replace_Tables implements Task {

	public function handle( array $args ): bool {
		$post_id = empty( $args[ 'post_id' ] ) ? 0 : absint( $args[ 'post_id' ] );

		$data        = \json_decode( get_post_field( 'post_content', $post_id, 'raw' ), true );
		$config      = new Copy_Configuration( $data );
		$destination = get_post_meta( $post_id, Copy_Manager::DESTINATION_BLOG, true );

		$src = $config->get_src();

		if ( empty( $src ) || empty( $destination ) ) {
			$error = new \WP_Error( 'missing_blog', __( 'Source and destination blogs both must exist to replace tables.', 'tribe' ) );
			do_action( Copy_Manager::TASK_ERROR_ACTION, static::class, $args, $error );

			return true;
		}

		$saved_options = $this->save_options( $destination );

		$this->copy_tables( $src, $destination );

		$this->restore_options( $destination, $saved_options );

		do_action( Copy_Manager::TASK_COMPLETE_ACTION, static::class, $args );

		return true;
	}

	private function copy_tables( $src, $destination ) {
		/** @var \wpdb $wpdb */
		global $wpdb;
		$tables = $wpdb->tables( 'blog', false, $src );

		$src_prefix  = $wpdb->get_blog_prefix( $src );
		$dest_prefix = $wpdb->get_blog_prefix( $destination );

		// May through errors if one of the source tables doesn't exist.
		// Not using "SHOW TABLES LIKE $table" because it doesn't work with temporary tables
		$suppress = $wpdb->suppress_errors();
		foreach ( $tables as $table ) {
			$wpdb->query( "DROP TABLE IF EXISTS `$dest_prefix$table`" );
			$wpdb->query( "CREATE TABLE IF NOT EXISTS `$dest_prefix$table` LIKE `$src_prefix$table`" );
			$wpdb->query( "INSERT `$dest_prefix$table` SELECT * FROM `$src_prefix$table`" );
		}
		$wpdb->suppress_errors( $suppress );
	}

	/**
	 * Preserve key options from the new blog
	 *
	 * @param int $blog_id
	 *
	 * @return array
	 */
	private function save_options( $blog_id ) {
		$option_keys = [
			'siteurl',
			'home',
			'upload_path',
			'fileupload_url',
			'upload_url_path',
			'admin_email',
			'blogname',
		];

		$option_keys = apply_filters( 'tribe/project/copy-blog/saved-options', $option_keys );

		$data = [];

		switch_to_blog( $blog_id );
		foreach ( $option_keys as $key ) {
			$data[ $key ] = get_option( $key );
		}
		restore_current_blog();

		return $data;
	}

	/**
	 * Restore the options we preserved from before the overwrite
	 *
	 * @param int   $blog_id
	 * @param array $options
	 *
	 * @return void
	 */
	private function restore_options( $blog_id, $options ) {
		switch_to_blog( $blog_id );

		// caches will be incorrect after direct DB copies
		wp_cache_delete( 'notoptions', 'options' );
		wp_cache_delete( 'alloptions', 'options' );

		foreach ( $options as $key => $value ) {
			update_option( $key, $value );
		}
		restore_current_blog();
	}

}