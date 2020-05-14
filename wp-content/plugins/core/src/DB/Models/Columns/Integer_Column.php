<?php
/**
 * Integer column data type.
 *
 * Included types: INTEGER, INT, SMALLINT, TINYINT, MEDIUMINT, BIGINT.
 *
 * @package AAN
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Models\Columns;

use Tribe\Project\DB\Exceptions\InvalidDataForColumnException;

/**
 * Class Integer_Column.
 */
class Integer_Column extends Base_Column {
	/**
	 * @inheritDoc
	 */
	public const TYPE = 'Integer';

	/**
	 * @inheritDoc
	 */
	protected $wpdb_type = '%d';

	/**
	 * @inheritDoc
	 */
	public function encode( $value ): int {
		if ( is_null( $value ) ) {
			return 0;
		}

		if ( ! is_scalar( $value ) ) {
			throw new InvalidDataForColumnException( $this, $value );
		}

		return (int) $value;
	}

	/**
	 * @inheritDoc
	 */
	public function decode( $value ): int {
		return (int) $value;
	}
}
