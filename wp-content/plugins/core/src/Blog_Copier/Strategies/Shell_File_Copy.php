<?php


namespace Tribe\Project\Blog_Copier\Strategies;

class Shell_File_Copy extends File_Copy_Strategy {

	protected function copy_files( $source, $destination ) {
		/**
		 * Filter the shell command used to copy files from the source to the destination
		 *
		 * @param string $command     The command that will be executed
		 * @param string $source      The source directory
		 * @param string $destination The destination directory
		 */
		$command = apply_filters( 'tribe/project/copy-blog/shell-copy-files/command', sprintf( 'cp -Rfp %s %s', $source, $destination ), $source, $destination );
		exec( $command );
	}

}
