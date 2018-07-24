<?php


namespace Tribe\Project\Blog_Copier\Strategies;


class Shell_File_Copy extends File_Copy_Strategy {

	protected function copy_files( $source, $destination ) {
		$command = apply_filters( 'tribe/project/copy-blog/shell-copy-files/command', sprintf( "cp -Rfp %s %s", $source, $destination ), $source, $destination );
		exec( $command );
	}

}