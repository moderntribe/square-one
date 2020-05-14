<?php
/**
 * Enumerated list column data type.
 *
 * For now this isn't an ENUM type, but just string with some enum value validation.
 *
 * Included types: VARCHAR.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Models\Columns;

use InvalidArgumentException;
use Tribe\Project\DB\Exceptions\InvalidDataForColumnException;

/**
 * Class Enum_Column.
 */
class Enum_Column extends Base_Column {
	/**
	 * @inheritDoc
	 */
	public const TYPE = 'Enum';

	/**
	 * @var array
	 */
	private $enum;

	/**
	 * @inheritDoc
	 */
	protected $wpdb_type = '%s';

	/**
	 * Enum_Column constructor.
	 *
	 * @param string $key      The key.
	 * @param string $sql_type SQL data type.
	 * @param array  $enum     The enum list.
	 * @param array  $args     Any additional arguments, e.g., AUTO-INCREMENT.
	 */
	public function __construct( string $key, string $sql_type, array $enum, array $args = [] ) {
		if ( count( $enum ) === 0 ) {
			throw new InvalidArgumentException( '$enum must contain at least 1 element.', 1 );
		}

		$this->enum = $enum;

		parent::__construct( $key, $sql_type, $args );
	}

	/**
	 * @inheritDoc
	 */
	public function encode( $value ): string {
		if ( is_null( $value ) ) {
			return $value;
		}

		if ( ! in_array( $value, $this->enum, true ) ) {
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
