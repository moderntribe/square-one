<?php
/**
 * String column data type.
 *
 * Included types: CHAR, VARCHAR, BLOB, and TEXT.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Models\Columns;

use Tribe\Project\DB\Exceptions\InvalidDataForColumnException;

/**
 * Class Text_Column.
 */
class String_Column extends Base_Column {
	/**
	 * @inheritDoc
	 */
	public const TYPE = 'String';

	/**
	 * @inheritDoc
	 */
	protected $wpdb_type = '%s';

	/**
	 * @inheritDoc
	 */
	public function encode( $value ): string {
		if ( is_null( $value ) ) {
			return '';
		}

		if ( ! is_string( $value ) ) {
			throw new InvalidDataForColumnException( $this, $value );
		}

		return (string) $value;
	}

	/**
	 * @inheritDoc
	 */
	public function decode( $value ): string {
		return (string) $value;
	}
}
