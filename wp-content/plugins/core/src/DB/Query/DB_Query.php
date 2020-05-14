<?php
/**
 * The Query factory.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Query;

use Tribe\Project\DB\Exceptions\NoRowsException;
use Tribe\Project\DB\Exceptions\WPDBException;
use Tribe\Project\DB\Models\Example;
use Tribe\Project\DB\Models\Storable;
use Tribe\Project\DB\Models\Storable_Factory;
use Tribe\Project\DB\Repository\WP_Table;
use Tribe\Project\DB\Repository\Clauses\Clause_Factory;
use wpdb;

/**
 * Class QotD_Query.
 */
class DB_Query {

	// Query arg keys.
	public const TYPE     = 'type';
	public const PER_PAGE = 'per_page';
	public const ID       = 'ID';
	public const WHERES   = 'wheres';

	/**
	 * @var array
	 */
	private $args;

	/**
	 * @var Clause_Factory
	 */
	private $clause_factory;

	/**
	 * @var wpdb
	 */
	private $wpdb;

	/**
	 * @var array Where clause fragments.
	 */
	private $wheres = [];

	/**
	 * @var Storable_Factory
	 */
	private $factory;

	/**
	 * QotD_Query constructor.
	 *
	 * @param array $args Query args.
	 *
	 * @see \WP_Query, get_defaults().
	 */
	public function __construct( array $args = [] ) {
		$this->wpdb           = $GLOBALS['wpdb'];
		$this->clause_factory = new Clause_Factory();
		$this->factory        = new Storable_Factory();
		$this->args           = array_merge( $this->get_defaults(), $args );

		$this->parse_args();
	}

	/**
	 * Sets a query arg.
	 *
	 * @param string $key   The arg key.
	 * @param mixed  $value The new value.
	 */
	public function set_query_arg( string $key, $value ): void {
		$this->args = array_merge( $this->args, [ $key => $value ] );
	}

	/**
	 * Resets a query arg.
	 *
	 * @param string $key The arg key.
	 */
	public function reset_query_arg( string $key ): void {
		unset( $this->args[ $key ] );
	}

	/**
	 * @return Storable|null Storables based on the query args.
	 */
	public function get_storable(): ?Storable {
		$results = $this->get_storables();

		return ! empty( $results ) ? $results[0] : null;
	}

	/**
	 * @return array Storables based on the query args.
	 */
	public function get_storables(): array {
		$results = [];

		$q = $this->get_query_class();

		if ( $q ) {
			try {
				$results = $q->get_many( $this->wheres );
			} catch ( NoRowsException $e ) {
				return [];
			}
		}

		return $results;
	}

	/**
	 * Update a storable.
	 *
	 * @param Storable      $storable The storable to update.
	 * @param Storable|null $original For patching, send the original Storable.
	 *
	 * @return int Number of affected rows.
	 */
	public function update_storable( Storable $storable, ?Storable $original = null ): int {
		$q = $this->get_query_class();

		return $q ? $q->update( $storable, $original ) : 0;
	}

	/**
	 * Update a storable field.
	 *
	 * @param string $key   The key to update.
	 * @param mixed  $value The new value.
	 *
	 * @return int Number of affected rows.
	 */
	public function update_storable_field( string $key, $value ): int {
		if ( empty( $this->wheres ) ) {
			return 0;
		}

		$q = $this->get_query_class();

		return $q ? $q->update_field( $key, $value, $this->wheres ) : 0;
	}

	/**
	 * Inserts a Storable.
	 *
	 * @param Storable $storable A Storable object.
	 *
	 * @return int Number of affected rows.
	 */
	public function insert_storable( Storable $storable ): int {
		$q = $this->get_query_class();

		try {
			$result = $q ? $q->insert( $storable ) : 0;
		} catch ( WPDBException $e ) {
			return 0;
		}

		return $result;
	}

	/**
	 * Inserts a Storable.
	 *
	 * @param array $storables An array of Storable objects.
	 *
	 * @return int Number of affected rows.
	 */
	public function insert_storables( array $storables ): int {
		$q = $this->get_query_class();

		try {
			$result = $q ? $q->insert_many( $storables ) : 0;
		} catch ( WPDBException $e ) {
			return 0;
		}

		return $result;
	}

	/**
	 * Pseudo-prepared statement to handle special query cases.
	 *
	 * @param string $case Enum list of query cases.
	 * @param array  $data Relevant data in order of appearance in the prepared statement.
	 *
	 * @return mixed
	 */
	public function special_query( string $case, array $data = [] ) {
		$result   = null;
		$q        = $this->get_query_class();
		$storable = $this->get_storable_instance();

		if ( ! $storable || ! $q ) {
			return null;
		}

		$prepare_sql = $storable->case_sql( $case );

		try {
			$result = $q->query( $prepare_sql, $data );
		} catch ( WPDBException $e ) {
			return 0;
		}

		return $result;
	}

	/**
	 * Pseudo-prepared statement to handle special get cases.
	 *
	 * @param string $case Enum list of query cases.
	 * @param array  $data Relevant data.
	 *
	 * @return mixed
	 */
	public function special_get( string $case, array $data = [] ) {
		$result   = null;
		$q        = $this->get_query_class();
		$storable = $this->get_storable_instance();

		if ( ! $storable || ! $q ) {
			return null;
		}

		$prepare_sql = $storable->case_sql( $case );

		try {
			$result = $q->get_results( $prepare_sql, $data );
		} catch ( WPDBException $e ) {
			return 0;
		}

		return $result;
	}

	/**
	 * Generate a WP_Table instance.
	 *
	 * @return null|WP_Table
	 */
	protected function get_query_class(): ?WP_Table {
		$storable = $this->get_storable_instance();

		if ( null === $storable ) {
			return null;
		}

		/**
		 * @var WP_Table
		 */
		return new WP_Table( $this->wpdb, $this->clause_factory, $storable, $this->factory );
	}

	/**
	 * Returns a Storable depending on the query type.
	 *
	 * @return Storable|null
	 */
	private function get_storable_instance(): ?Storable {
		switch ( $this->args[ self::TYPE ] ) {
			case Example::NAME:
				$storable = $this->factory->make( Example::class );
				break;
		}

		return $storable ?? null;
	}

	/**
	 * @return array The default args.
	 */
	private function get_defaults(): array {
		return [
			self::TYPE     => Profile::NAME,
			self::PER_PAGE => get_option( 'posts_per_page' ),
		];
	}

	/**
	 * Set up the clauses from the query args.
	 */
	protected function parse_args(): void {
		if ( isset( $this->args[ self::ID ] ) ) {
			$this->wheres[] = $this->wpdb->prepare( 'ID = %d', $this->args[ self::ID ] );
		}
		if ( isset( $this->args[ self::WHERES ] ) ) {
			$wheres       = is_array( $this->args[ self::WHERES ] ) ? $this->args[ self::WHERES ] : explode( ',', $this->args[ self::WHERES ] );
			$this->wheres = array_merge( $this->wheres, $wheres );
		}
	}
}
