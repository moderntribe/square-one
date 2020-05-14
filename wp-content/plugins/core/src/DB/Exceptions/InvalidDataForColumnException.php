<?php
/**
 * Exception for column data types.
 *
 * @package AAN
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Exceptions;

use RuntimeException;
use Tribe\Project\DB\Models\Columns\Base_Column;

/**
 * Class InvalidDataForColumnException.
 */
class InvalidDataForColumnException extends RuntimeException {

	/**
	 * @var Base_Column
	 */
	private $column;

	/**
	 * @var mixed
	 */
	private $data;

	/**
	 * @inheritDoc
	 */
	public function __construct( Base_Column $column, $data = null ) {
		$message = 'Incorrect data type passed to ' . $column::TYPE . ' column.';

		parent::__construct( $message );
	}
}
