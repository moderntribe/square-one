<?php
/**
 * Exception to handle attempts to save a Storable in the wrong table.
 *
 * @package AAN
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Exceptions;

use RuntimeException;

/**
 * Class IncompatibleStorableException.
 */
class IncompatibleStorableException extends RuntimeException {
	/**
	 * IncompatibleStorableException constructor.
	 *
	 * @param string $storable The Storable.
	 * @param string $table    The table you're attempting to save it into.
	 */
	public function __construct( string $storable, string $table ) {
		parent::__construct( sprintf(
			'%1$s cannot be saved in %2$s',
			ucfirst( $storable ),
			ucfirst( $table )
		) );
	}
}
