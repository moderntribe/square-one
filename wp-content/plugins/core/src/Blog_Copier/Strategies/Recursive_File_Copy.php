<?php


namespace Tribe\Project\Blog_Copier\Strategies;

/**
 * Class Recursive_File_Copy
 *
 * Recursively walk the uploads directory tree, copying files one at a time.
 * Useful for hosting environments that don't support exec
 */
class Recursive_File_Copy extends File_Copy_Strategy {

	protected function copy_files( $source, $destination ) {
		$dir = opendir( $source );
		wp_mkdir_p( $destination );
		while ( false !== ( $file = readdir( $dir ) ) ) {
			if ( ( $file != '.' ) && ( $file != '..' ) ) {
				if ( is_dir( $source . '/' . $file ) ) {
					$this->copy_files( $source . '/' . $file, $destination . '/' . $file );
				} else {
					copy( $source . '/' . $file, $destination . '/' . $file );
				}
			}
		}
		closedir( $dir );
	}

}
