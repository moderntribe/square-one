<?php
/**
 * Boolean column data type.
 *
 * Included types: TINYINT, BOOLEAN.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Models\Columns;

/**
 * Class Bool_Column.
 */
class Bool_Column extends Base_Column {
	/**
	 * @inheritDoc
	 */
	public const TYPE = 'Boolean';

	/**
	 * @inheritDoc
	 */
	protected $wpdb_type = '%d';

	/**
	 * Bool_Column constructor.
	 *
	 * @param string $key  The key.
	 * @param array  $args Any additional arguments, e.g., AUTO-INCREMENT.
	 */
	public function __construct( string $key, array $args = [] ) {
		parent::__construct( $key, 'TINYINT(1)', $args );
	}

	/**
	 * @inheritDoc
	 */
	public function encode( $value ): int {
		return 0 === $value ? 1 : 0;
	}

	/**
	 * @inheritDoc
	 */
	public function decode( $value ): bool {
		return (bool) $value;
	}
}
