<?php

use \WP_CLI\Utils;

/**
 * Verify WordPress core checksums.
 *
 * @package wp-cli
 */
class Checksum_Command extends WP_CLI_Command {

	private static function _read( $url ) {
		$headers = array('Accept' => 'application/json');
		$response = Utils\http_request( 'GET', $url, null, $headers, array( 'timeout' => 30 ) );
		if ( 200 === $response->status_code ) {
			return $response->body;
		} else {
			WP_CLI::error( "Couldn't fetch response from {$url} (HTTP code {$response->status_code})." );
		}
	}

	private function get_download_offer( $locale ) {
		$out = unserialize( self::_read(
			'https://api.wordpress.org/core/version-check/1.6/?locale=' . $locale ) );

		$offer = $out['offers'][0];

		if ( $offer['locale'] != $locale ) {
			return false;
		}

		return $offer;
	}


	/**
	 * Verify WordPress files against WordPress.org's checksums.
	 *
	 * Downloads md5 checksums for the current version from WordPress.org, and
	 * compares those checksums against the currently installed files.
	 *
	 * For security, avoids loading WordPress when verifying checksums.
	 *
	 * ## OPTIONS
	 *
	 * [--version=<version>]
	 * : Verify checksums against a specific version of WordPress.
	 *
	 * [--locale=<locale>]
	 * : Verify checksums against a specific locale of WordPress.
	 *
	 * ## EXAMPLES
	 *
	 *     # Verify checksums
	 *     $ wp core verify-checksums
	 *     Success: WordPress install verifies against checksums.
	 *
	 *     # Verify checksums for given WordPress version
	 *     $ wp core verify-checksums --version=4.0
	 *     Success: WordPress install verifies against checksums.
	 *
	 *     # Verify checksums for given locale
	 *     $ wp core verify-checksums --locale=en_US
	 *     Success: WordPress install verifies against checksums.
	 *
	 *     # Verify checksums for given locale
	 *     $ wp core verify-checksums --locale=ja
	 *     Warning: File doesn't verify against checksum: wp-includes/version.php
	 *     Warning: File doesn't verify against checksum: readme.html
	 *     Warning: File doesn't verify against checksum: wp-config-sample.php
	 *     Error: WordPress install doesn't verify against checksums.
	 *
	 * @when before_wp_load
	 */
	public function core( $args, $assoc_args ) {
		global $wp_version, $wp_local_package;

		if ( ! empty( $assoc_args['version'] ) ) {
			$wp_version = $assoc_args['version'];
		}

		if ( ! empty( $assoc_args['locale'] ) ) {
			$wp_local_package = $assoc_args['locale'];
		}

		if ( empty( $wp_version ) ) {
			$details = self::get_wp_details();
			$wp_version = $details['wp_version'];

			if ( empty( $wp_local_package ) ) {
				$wp_local_package = $details['wp_local_package'];
			}
		}

		$checksums = self::get_core_checksums( $wp_version,
			! empty( $wp_local_package ) ? $wp_local_package : 'en_US' );

		if ( ! is_array( $checksums ) ) {
			WP_CLI::error( "Couldn't get checksums from WordPress.org." );
		}

		$has_errors = false;
		foreach ( $checksums as $file => $checksum ) {
			// Skip files which get updated
			if ( 'wp-content' == substr( $file, 0, 10 ) ) {
				continue;
			}

			if ( ! file_exists( ABSPATH . $file ) ) {
				WP_CLI::warning( "File doesn't exist: {$file}" );
				$has_errors = true;
				continue;
			}

			$md5_file = md5_file( ABSPATH . $file );
			if ( $md5_file !== $checksum ) {
				WP_CLI::warning( "File doesn't verify against checksum: {$file}" );
				$has_errors = true;
			}
		}

		$core_checksums_files = array_filter( array_keys( $checksums ), array( $this, 'only_core_files_filter' ) );
		$core_files           = $this->get_wp_core_files();
		$additional_files     = array_diff( $core_files, $core_checksums_files );

		if ( ! empty( $additional_files ) ) {
			foreach ( $additional_files as $additional_file ) {
				WP_CLI::warning( "File should not exist: {$additional_file}" );
			}
		}

		if ( ! $has_errors ) {
			WP_CLI::success( "WordPress install verifies against checksums." );
		} else {
			WP_CLI::error( "WordPress install doesn't verify against checksums." );
		}
	}

	private function get_wp_core_files() {
		$core_files = array();
		try {
			$files = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( ABSPATH, RecursiveDirectoryIterator::SKIP_DOTS ),
				RecursiveIteratorIterator::CHILD_FIRST
			);
			foreach ( $files as $file_info ) {
				$pathname = substr( $file_info->getPathname(), strlen( ABSPATH ) );
				if ( $file_info->isFile() && ( 0 === strpos( $pathname, 'wp-admin/' ) || 0 === strpos( $pathname, 'wp-includes/' ) ) ) {
					$core_files[] = str_replace( ABSPATH, '', $file_info->getPathname() );
				}
			}
		} catch( Exception $e ) {
			WP_CLI::error( $e->getMessage() );
		}

		return $core_files;
	}

	private function only_core_files_filter( $file ) {
		return ( 0 === strpos( $file, 'wp-admin/' ) || 0 === strpos( $file, 'wp-includes/' ) );
	}

	/**
	 * Get version information from `wp-includes/version.php`.
	 *
	 * @return array {
	 *     @type string $wp_version The WordPress version.
	 *     @type int $wp_db_version The WordPress DB revision.
	 *     @type string $tinymce_version The TinyMCE version.
	 *     @type string $wp_local_package The TinyMCE version.
	 * }
	 */
	private static function get_wp_details() {
		$versions_path = ABSPATH . 'wp-includes/version.php';

		if ( ! is_readable( $versions_path ) ) {
			WP_CLI::error(
				"This does not seem to be a WordPress install.\n" .
				"Pass --path=`path/to/wordpress` or run `wp core download`." );
		}

		$version_content = file_get_contents( $versions_path, null, null, 6, 2048 );

		$vars   = array( 'wp_version', 'wp_db_version', 'tinymce_version', 'wp_local_package' );
		$result = array();

		foreach ( $vars as $var_name ) {
			$result[ $var_name ] = self::find_var( $var_name, $version_content );
		}

		return $result;
	}

	/**
	 * Search for the value assigned to variable `$var_name` in PHP code `$code`.
	 *
	 * This is equivalent to matching the `\$VAR_NAME = ([^;]+)` regular expression and returning
	 * the first match either as a `string` or as an `integer` (depending if it's surrounded by
	 * quotes or not).
	 *
	 * @param string $var_name Variable name to search for.
	 * @param string $code PHP code to search in.
	 *
	 * @return int|string|null
	 */
	private static function find_var( $var_name, $code ) {
		$start = strpos( $code, '$' . $var_name . ' = ' );

		if ( ! $start ) {
			return null;
		}

		$start = $start + strlen( $var_name ) + 3;
		$end   = strpos( $code, ";", $start );

		$value = substr( $code, $start, $end - $start );

		if ( $value[0] = "'" ) {
			return trim( $value, "'" );
		} else {
			return intval( $value );
		}
	}

	/**
	 * Security copy of the core function with Requests - Gets the checksums for the given version of WordPress.
	 *
	 * @param string $version Version string to query.
	 * @param string $locale  Locale to query.
	 * @return bool|array False on failure. An array of checksums on success.
	 */
	private static function get_core_checksums( $version, $locale ) {
		$url = 'https://api.wordpress.org/core/checksums/1.0/?' . http_build_query( compact( 'version', 'locale' ), null, '&' );

		$options = array(
			'timeout' => 30
		);

		$headers = array(
			'Accept' => 'application/json'
		);
		$response = Utils\http_request( 'GET', $url, null, $headers, $options );

		if ( ! $response->success || 200 != $response->status_code )
			return false;

		$body = trim( $response->body );
		$body = json_decode( $body, true );

		if ( ! is_array( $body ) || ! isset( $body['checksums'] ) || ! is_array( $body['checksums'] ) )
			return false;

		return $body['checksums'];
	}

}
