<?php
/**
 * Thrown for invalid indexable types.
 *
 * @package    SquareOne
 * @subpackage API
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Exceptions;

use RuntimeException;

/**
 * Class IndexableNotFoundException.
 */
class IndexableNotFoundException extends RuntimeException {
	/**
	 * IncompatibleStorableException constructor.
	 *
	 * @param string $classname
	 */
	public function __construct( string $classname ) {
		parent::__construct( sprintf(
			'Indexable with type of %s cannot be found.',
			$classname
		) );
	}
}
