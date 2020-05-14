<?php /** @noinspection PhpHierarchyChecksInspection */
/**
 * The abstract custom tables class.
 *
 * @package AAN
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Repository;

use Exception;
use RuntimeException;
use Tribe\Project\DB\Models\Storable_Factory;
use Tribe\Project\DB\Repository\Clauses\Clause_Factory;
use Tribe\Project\DB\Exceptions\IncompatibleStorableException;
use Tribe\Project\DB\Exceptions\NoRowsException;
use Tribe\Project\DB\Exceptions\WPDBException;
use Tribe\Project\DB\Models\Columns\Encodable;
use Tribe\Project\DB\Models\Storable;
use wpdb;

/**
 * Class WP_Table.
 */
class WP_Table extends Repository {
	/**
	 * @var string The table slug.
	 */
	protected $slug;

	/**
	 * @var Storable
	 */
	protected $storable;

	/**
	 * @var wpdb The WPDb instance.
	 */
	private $wpdb;

	/**
	 * @var Clause_Factory
	 */
	private $clause_factory;
	/**
	 * @var Storable_Factory
	 */
	private $factory;

	/**
	 * WP_Table constructor.
	 *
	 * @param wpdb             $wpdb           The wpdb service.
	 * @param Clause_Factory   $clause_factory Generates SQL query clauses.
	 * @param Storable         $storable       A (usually empty) Storable object.
	 * @param Storable_Factory $factory        Storable factory.
	 */
	public function __construct( wpdb $wpdb, Clause_Factory $clause_factory, Storable $storable, Storable_Factory $factory ) {
		$this->clause_factory = $clause_factory;
		$this->storable       = $storable;
		$this->wpdb           = $wpdb;
		$this->factory        = $factory;
		$this->slug           = $storable::NAME;
	}

	/**
	 * @inheritDoc
	 */
	public function get_prefixed_name(): string {
		return $this->wpdb->prefix . $this->storable::get_table_name();
	}

	/**
	 * Create or upgrade the table.
	 *
	 * @param bool $recreate Drop the table, then recreate it.
	 *
	 * @return bool
	 */
	public function db_upgrade( bool $recreate = false ): bool {
		$charset_collate = $this->get_collation();
		$columns         = [];

		foreach ( $this->storable->get_columns() as $column ) {
			$columns[] = $column->get_create_column();
		}

		$columns[]  = sprintf( 'PRIMARY KEY  (%s)', $this->storable->get_primary_key() );
		$col_string = implode( ",\n ", $columns );

		$table = $this->get_prefixed_name();
		$sql   = "CREATE TABLE $table ( $col_string ) $charset_collate";

		if ( $recreate ) {
			$this->db_cleanup( true );
		}

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		return true;
	}

	/**
	 * Rename the table.
	 *
	 * @param string $previous_name
	 * @param string $new_name
	 *
	 * @return bool
	 */
	public function db_rename( string $previous_name, string $new_name ): bool {
		$sql = sprintf(
			'ALTER TABLE %1$s RENAME TO %2$s',
			sanitize_title( $previous_name ),
			sanitize_title( $new_name )
		);

		return (bool) $this->wpdb->query( $sql );
	}

	/**
	 * Drop the table.
	 *
	 * @param bool $are_you_sure Dev UI.
	 *
	 * @return bool
	 */
	public function db_cleanup( bool $are_you_sure = false ): bool {
		if ( ! $are_you_sure ) {
			return false;
		}

		return (bool) $this->wpdb->query(
			sprintf(
				'DROP TABLE IF EXISTS %s',
				$this->get_prefixed_name()
			)
		);
	}

	/**
	 * @return string The charset collation from WPDb.
	 */
	private function get_collation(): string {
		return $this->wpdb->get_charset_collate();
	}

	/**
	 * Insert a Storable.
	 *
	 * @param Storable $record
	 *
	 * @return int
	 */
	public function insert( Storable $record ): int {
		$result        = null;
		$wpdb_prepares = $this->get_wpdb_prepares();
		$table         = $this->get_prefixed_name();
		$keys          = sanitize_text_field( implode( ', ', $record->get_keys() ) );
		$values        = $this->encode_storable_values( $record );

		$sql = $this->wpdb->prepare(
			"INSERT INTO $table ( $keys ) VALUES( $wpdb_prepares );",
			$values
		);

		$result = (int) $this->wpdb->query( $sql );

		return $this->return( $result );
	}

	/**
	 * Insert many records.
	 *
	 * @param array $records
	 *
	 * @return int
	 */
	public function insert_many( array $records ): int {
		$result = null;

		/**
		 * Filter whether transactions are supported in SQL version.
		 *
		 * @param bool
		 */
		if ( ! apply_filters( 'tribe/project/db/transactions_support', true ) ) {
			$this->wpdb->query( 'START TRANSACTION;' );

			try {
				foreach ( $records as $i => $record ) {
					$this->insert( $record );
				}
			} catch ( Exception $e ) {
				$this->wpdb->query( 'ROLLBACK;' );

				throw new WPDBException( $e->getMessage() );
			}

			$result = $this->wpdb->query( 'COMMIT;' );

			return $this->return( $result );
		}

		/**
		 * Transactions unsupported, so perform each transaction individually.
		 */
		try {
			foreach ( $records as $i => $record ) {
				$result = $this->insert( $record );
			}
		} catch ( RuntimeException $e ) {
			throw new WPDBException( sprintf( '%s for record %d', $e->getMessage(), $i ) );
		}

		return $this->return( $result );
	}

	/**
	 * @inheritDoc
	 */
	public function get( string $key ): Storable {
		$result = $this->get_many(
			[ $this->storable->get_primary_key() . '=' . $key ]
		);

		if ( ! isset( $result[0] ) ) {
			throw new NoRowsException();
		}

		return $result[0];
	}

	/**
	 * @inheritDoc
	 */
	public function get_many( array $wheres = [] ): array {
		$results = [];
		$from    = $this->clause_factory->get_clause( Clause_Factory::FROM, [ $this->get_prefixed_name() ] );
		$where   = $this->clause_factory->get_clause( Clause_Factory::WHERE, $wheres );

		$sql     = "SELECT * $from $where";
		$records = $this->wpdb->get_results( $sql, ARRAY_A );

		// Process errors.
		if ( ! empty( $this->wpdb->last_error ) ) {
			throw new WPDBException( $this->wpdb->last_error );
		}

		// Process empty results.
		if ( empty( $records ) ) {
			throw new NoRowsException();
		}

		// Decode each record by hydrating the Storable.
		foreach ( $records as $record ) {
			try {
				$results[] = $this->decode_table_record( $record );
			} catch ( RuntimeException $e ) {
				continue;
			}
		}

		return $results;
	}

	/**
	 * @inheritDoc
	 */
	public function update( Storable $storable, ?Storable $original = null ): int {
		$result      = null;
		$table       = $this->get_prefixed_name();
		$columns     = $this->storable->get_columns();
		$where       = $this->clause_factory->get_clause( Clause_Factory::WHERE, [] );
		$assignments = [];

		foreach ( $storable as $property => $value ) {
			// If original is sent, then patch the differing props.
			if ( ! $original || $original->$property === $value ) {
				continue;
			}

			$prepare_sql   = sprintf( '%1$s = %2$s', sanitize_text_field( $property ), $columns[ $property ]->get_wpdb_type() );
			$assignments[] = $this->wpdb->prepare( $prepare_sql, $columns[ $property ]->encode( $value ) );
		}

		$assignments = implode( ', ', $assignments );

		$sql = "UPDATE $table SET $assignments $where;";

		$result = $this->wpdb->query( $sql );

		return $this->return( (int) $result );
	}

	/**
	 * @inheritDoc
	 */
	public function update_field( string $key, $value, array $wheres = [] ): int {
		$result = $this->update_fields(
			[ $key => $value ],
			$wheres
		);

		return $this->return( $result );
	}

	/**
	 * @inheritDoc
	 */
	public function update_fields( array $key_value_pairs, array $wheres = [] ): int {
		$result      = null;
		$table       = $this->get_prefixed_name();
		$columns     = $this->storable->get_columns();
		$where       = $this->clause_factory->get_clause( Clause_Factory::WHERE, $wheres );
		$assignments = [];

		foreach ( $key_value_pairs as $key => $value ) {
			if ( ! array_key_exists( $key, $columns ) ) {
				throw new IncompatibleStorableException( $this->storable::get_classname(), $this->get_prefixed_name() );
			}

			$prepare_sql   = sprintf( '%1$s = %2$s', sanitize_text_field( $key ), $columns[ $key ]->get_wpdb_type() );
			$assignments[] = $this->wpdb->prepare( $prepare_sql, $columns[ $key ]->encode( $value ) );
		}

		$assignments = implode( ', ', $assignments );

		$sql = "UPDATE $table SET $assignments $where;";

		$result = $this->wpdb->query( $sql );

		return $this->return( (int) $result );
	}

	/**
	 * @inheritDoc
	 */
	public function delete( string $key ): int {
		return $this->delete_many( [ $this->storable->get_primary_key() . '=' . $key ] );
	}

	/**
	 * @inheritDoc
	 */
	public function delete_many( array $wheres = [ '1=0' ] ): int {
		$result = null;
		$table  = $this->get_prefixed_name();
		$where  = $this->clause_factory->get_clause( Clause_Factory::WHERE, $wheres );

		$sql = sprintf(
			"DELETE FROM $table %s;",
			sanitize_text_field( $where )
		);

		$result = $this->wpdb->query( $sql );

		return $this->return( $result );
	}

	/**
	 * Fires prepared SQL statements that return only the number of records affected.
	 *
	 * @param string $prepare_sql The WPDb-prepared SQL.
	 * @param array  $data        The values to populate it with.
	 *
	 * @return int
	 * @see wpdb::query()
	 *
	 */
	public function query( string $prepare_sql, array $data = [] ): int {
		$sql = $this->wpdb->prepare( $prepare_sql, ... array_values( $data ) );

		$return = $this->wpdb->query( $sql );

		return $return;
	}

	/**
	 * Gets results for SQL statements.
	 *
	 *
	 * @param string $prepare_sql The WPDb-prepared SQL.
	 * @param array  $data        The values to populate it with.
	 *
	 * @return array
	 * @see wpdb::get_results()
	 */
	public function get_results( string $prepare_sql, array $data = [] ): array {
		$sql = $this->wpdb->prepare( $prepare_sql, ... array_values( $data ) );

		return $this->wpdb->get_results( $sql );
	}

	/**
	 * Abstracts some return logic.
	 *
	 * @param int $rows_affected Affected rows.
	 *
	 * @return int Affected rows.
	 * @throws WPDBException
	 * @throws NoRowsException
	 */
	protected function return( int $rows_affected = 1 ): int {
		$success = empty( $this->wpdb->last_error );

		if ( ! $success ) {
			throw new WPDBException( $this->wpdb->last_error );
		}

		if ( ! $rows_affected ) {
			throw new NoRowsException();
		}

		return $rows_affected;
	}

	/**
	 * Encodes a DB value based on its key and Encodable class.
	 *
	 * @param string $key   The DB key.
	 * @param mixed  $value The value.
	 *
	 * @return mixed The encoded value.
	 * @throws IncompatibleStorableException
	 * @see Encodable
	 */
	protected function encode_single_value( string $key, $value ) {
		$columns = $this->storable->get_columns();

		if ( array_key_exists( $key, $columns ) ) {
			/**
			 * @var Encodable The column for encoding and decoding values.
			 */
			$encodable = $columns[ $key ]; // Get the encodable object.

			return $encodable->encode( $value );
		}

		throw new IncompatibleStorableException( $this->storable::get_name(), $this->get_slug() );
	}

	/**
	 * Uses the table columns to properly encode the object properties.
	 *
	 * @param Storable $record The object
	 *
	 * @return array A flat array of properly encoded values.
	 * @throws IncompatibleStorableException
	 */
	protected function encode_storable_values( Storable $record ): array {
		$values = [];

		// Loop through Storable properties and encode them.
		foreach ( $record as $key => $value ) {
			$values[] = $this->encode_single_value( $key, $value );
		}

		return $values;
	}

	/**
	 * Creates a Storable object from the table record.
	 *
	 * @param array $record The record as an associative array.
	 *
	 * @return Storable
	 */
	protected function decode_table_record( array $record ): Storable {
		return $this->factory->make_and_hydrate( $this->storable::get_classname(), $record );
	}

	/**
	 * Get the placeholders for wpdb prepare statements.
	 *
	 * @return string
	 */
	private function get_wpdb_prepares(): string {
		$return  = [];
		$columns = $this->storable->get_columns();

		foreach ( $columns as $key => $encodable ) {
			$return[ $key ] = $encodable->get_wpdb_type();
		}

		return implode( ', ', $return );
	}
}
