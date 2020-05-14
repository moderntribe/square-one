<?php
/**
 * Interface for any model that can be saved as a record.
 *
 * @package AAN
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Models;

use Tribe\Project\DB\Exceptions\IncompatibleStorableException;

/**
 * Interface Storable.
 */
interface Storable {

	/**
	 * Uses raw WPDb results to hydrate the object.
	 *
	 * @param array $data
	 *
	 * @return Storable
	 * @throws IncompatibleStorableException
	 */
	public function hydrate( array $data ): Storable;

	/**
	 * @return string The name of the table.
	 */
	public static function get_name(): string;

	/**
	 * @return string The class name.
	 */
	public static function get_classname(): string;

	/**
	 * Gets the prefixed name with prefix.
	 *
	 * @return string
	 */
	public static function get_table_name(): string;

	/**
	 * Should mirror the properties initialized in the constructors.
	 *
	 * @return array Get the Storable's model keys.
	 * @see \Tribe\Project\DB\Table\Base_Table
	 */
	public function get_keys(): array;

	/**
	 * Return an array of columns.
	 *
	 * @return array
	 */
	public function get_columns(): array;

	/**
	 * @return string The primary key.
	 */
	public function get_primary_key(): string;

	/**
	 * Generates custom queries that return results.
	 *
	 * @param string $case Enum list of query types defined in the query child.
	 * @param array  $data Any relevant data.
	 *
	 * @return string the SQL.
	 */
	public function case_sql( string $case, array $data = [] ): string;
}
