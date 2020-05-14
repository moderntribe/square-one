<?php
/**
 * Exception to handle WPDb errors.
 *
 * @package AAN
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Exceptions;

use RuntimeException;

/**
 * Class WPDBException.
 */
class WPDBException extends RuntimeException {
	/**
	 * @inheritDoc
	 */
	public function __construct( string $last_wpdb_error ) {
		$message = 'WPDB encountered an error: ' . $last_wpdb_error;

		parent::__construct( $message );
	}
}
