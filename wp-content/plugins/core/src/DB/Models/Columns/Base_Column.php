<?php
/**
 * Base column.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Models\Columns;

/**
 * Class Base_Column.
 */
abstract class Base_Column implements Encodable {

	/**
	 * The column data type.
	 */
	public const NAME = 'Base';

	/**
	 * @var string The column key.
	 */
	private $key;

	/**
	 * @var string The SQL data type, e.g., VARCHAR(11).
	 */
	private $sql_type;

	/**
	 * @var string The placeholder to use for WPDB prepare statements.
	 */
	protected $wpdb_type;

	/**
	 * @var array Additional field creation arguments, e.g., 'NOT NULL'.
	 */
	private $args;

	/**
	 * Base_Column constructor.
	 *
	 * @param string   $key      The key.
	 * @param string   $sql_type SQL data type.
	 * @param array    $args     Any additional arguments, e.g., AUTO-INCREMENT.
	 */
	public function __construct( string $key, string $sql_type, array $args = [] ) {
		$this->sql_type = $sql_type;
		$this->key      = $key;
		$this->args     = $args;
	}

	/**
	 * @inheritDoc
	 */
	abstract public function encode( $value );

	/**
	 * @inheritDoc
	 */
	abstract public function decode( $value );

	/**
	 * @return string
	 */
	public function get_key(): string {
		return $this->key;
	}

	/**
	 * @return string
	 */
	public function get_sql_type(): string {
		return $this->sql_type;
	}

	/**
	 * @return string
	 */
	public function get_wpdb_type(): string {
		return $this->wpdb_type;
	}

	/**
	 * @return array
	 */
	public function get_args(): array {
		return $this->args;
	}

	/**
	 * Generates line for creating the column.
	 *
	 * @return string
	 * @see \Tribe\Project\DB\Repository\WP_Table::db_upgrade()
	 *
	 */
	public function get_create_column(): string {
		return sprintf(
			'%1$s %2$s %3$s',
			$this->key,
			$this->sql_type,
			implode( " ", $this->args )
		);
	}
}
