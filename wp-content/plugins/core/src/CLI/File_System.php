<?php

namespace Tribe\Project\CLI;

class File_System {

	public function create_directory( $directory ) {
		clearstatcache();
		if ( file_exists( $directory ) ) {
			\WP_CLI::error( 'Sorry... ' . $directory . ' directory already exists' );
		}
		if ( ! mkdir( $directory ) && ! is_dir( $directory ) ) {
			\WP_CLI::error( 'Sorry...something went wrong when we tried to create ' . $directory );
		}
	}

	public function write_file( $file, $contents, $overwrite = false ) {
		if ( file_exists( $file) && ! $overwrite ) {
			\WP_CLI::error( 'Sorry... ' . $file . ' already exists.' );
		}
		if ( ! $handle = fopen( $file, 'w' ) ) {
			\WP_CLI::error( 'Sorry...something went wrong when we tried to write to ' . $file );
		}

		fwrite( $handle, $contents );
		return $handle;
	}

	public function insert_into_existing_file( $file, $new_line, $below_line ) {
		if ( ! $handle = fopen( $file, 'r+' ) ) {
			\WP_CLI::error( 'Sorry.. ' . $file . ' could not be opened.' );
		}

		$contents = '';
		while (! feof ( $handle ) ) {
			$line = fgets( $handle );
			$contents .= $line;
			if ( strpos( $line, $below_line ) !== false ) {
				$contents .= $new_line;
			}
		}

		if ( ! fclose( $handle ) ) {
			\WP_CLI::error( 'Sorry.. ' . $file . ' an error has occurred.' );
		}

		$this->write_file( $file, $contents, true );
	}

	public function get_file( $path ) {
		return file_get_contents( $path );
	}

	/**
	 * Thanks stackoverflow.
	 * gist: https://gist.github.com/stemar/bb7c5cd2614b21b624bf57608f995ac0
	 *
	 * @param     $array
	 * @param int $internal_indent
	 *
	 * @return mixed
	 */
	public function format_array_for_file( $array, $array_indent = 0, $internal_indent = 4 ) {
		$object = json_decode( str_replace( [ '(', ')' ], [
			'&#40',
			'&#41',
		], json_encode( $array ) ), true );
		$export = str_replace( [ 'array (', ')', '&#40', '&#41' ], [
			'[',
			']',
			'(',
			')',
		], var_export( $object, true ) );
		$export = preg_replace( "/ => \n[^\S\n]*\[/m", ' => [', $export );
		$export = preg_replace( "/ => \[\n[^\S\n]*\]/m", ' => []', $export );
		$spaces = str_repeat( ' ', $internal_indent );
		$export = preg_replace( "/([ ]{2})(?![^ ])/m", $spaces, $export );
		$export = preg_replace( "/^([ ]{2})/m", $spaces, $export );

		echo $export;
		$lines = explode( PHP_EOL, $export );
		print_r( $lines );die;
		$export = '';

		foreach ( $lines as $line ) {
			$export .= str_repeat( ' ', $array_indent ) . $line . PHP_EOL;
		}

		return $export;
	}

	public function constant_from_class( $class_name ) {
		return strtoupper( str_replace( '_', '', $class_name ) );
	}

}
