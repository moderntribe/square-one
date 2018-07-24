<?php


namespace Tribe\Project\Blog_Copier\Strategies;


abstract class File_Copy_Strategy {
	public function handle_copy( $src_blog_id, $dest_blog_id ) {
		@ini_set( 'memory_limit', '2048M' );
		$from = $this->get_source_directory( $src_blog_id );
		$to   = $this->get_destination_directory( $dest_blog_id );
		$this->copy_files( $from, $to );
	}

	protected function get_source_directory( $src_blog_id ) {
		switch_to_blog( $src_blog_id );
		$dir_info = wp_upload_dir();
		$from     = str_replace( ' ', "\\ ", trailingslashit( $dir_info[ 'basedir' ] ) . '*' ); // * necessary with GNU cp, doesn't hurt anything with BSD cp
		restore_current_blog();

		/**
		 * Filter the source directory for the file copy
		 *
		 * @param string $from        The source directory path
		 * @param int    $src_blog_id The source blog ID
		 */
		return apply_filters( 'tribe/project/copy-blog/shell-copy-files/from', $from, $src_blog_id );
	}

	protected function get_destination_directory( $dest_blog_id ) {
		switch_to_blog( $dest_blog_id );
		$dir_info = wp_upload_dir();
		$to       = str_replace( ' ', "\\ ", trailingslashit( $dir_info[ 'basedir' ] ) );
		restore_current_blog();

		/**
		 * Filter the destination directory for the file copy
		 *
		 * @param string $to           The destination directory path
		 * @param int    $dest_blog_id The destination blog ID
		 */
		return apply_filters( 'tribe/project/copy-blog/shell-copy-files/to', $to, $dest_blog_id );
	}

	abstract protected function copy_files( $source, $destination );
}