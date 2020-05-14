<?php
/**
 * Exception to handle when no rows were updated.
 *
 * @package AAN
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Exceptions;

use RuntimeException;

/**
 * Class NoRowsAffectedException.
 */
class NoRowsException extends RuntimeException {
	/**
	 * @inheritDoc
	 */
	public function __construct() {
		parent::__construct( 'WPDB did not find or update any rows.' );
	}
}
