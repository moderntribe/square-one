<?php
/**
 * Interface for custom tables.
 *
 * @package AAN
 */

declare( strict_types=1 );

namespace Tribe\Project\DB\Repository;

use Tribe\Project\DB\Models\Storable;

/**
 * Interface Custom_TableInterface.
 */
interface Persistent {
	/**
	 * @return string The classname of the model used for this table.
	 */
	public function get_model_classname(): string;

	/**
	 * Gets the slug for the table.
	 *
	 * @return string
	 */
	public function get_slug(): string;

	/**
	 * Insert a Storable.
	 *
	 * @param Storable $record
	 *
	 * @return int
	 */
	public function insert( Storable $record ): int;

	/**
	 * Insert many records.
	 *
	 * @param array $records
	 *
	 * @return int
	 */
	public function insert_many( array $records ): int;

	/**
	 * Get a record by primary key.
	 *
	 * @param string $key
	 *
	 * @return Storable
	 */
	public function get( string $key ): Storable;

	/**
	 * Get many records.
	 *
	 * @param array $wheres The where conditions.
	 *
	 * @return array
	 */
	public function get_many( array $wheres = [] ): array;

	/**
	 * Update a record field.
	 *
	 * @param Storable      $storable The Storable to update.
	 * @param Storable|null $original For patching, send the original Storable.
	 *
	 * @return int
	 */
	public function update( Storable $storable, ?Storable $original = null ): int;

	/**
	 * Update a record field.
	 *
	 * @param string $key    Field key.
	 * @param mixed  $value  New value.
	 * @param array  $wheres Where conditions.
	 *
	 * @return int
	 */
	public function update_field( string $key, $value, array $wheres = [] ): int;

	/**
	 * Update multiple record fields.
	 *
	 * @param array $key_value_pairs Assoc array of Storable properties and their new values.
	 * @param array $wheres          Where conditions.
	 *
	 * @return int
	 */
	public function update_fields( array $key_value_pairs, array $wheres = [] ): int;

	/**
	 * Delete a record.
	 *
	 * @param string $key The primary key.
	 *
	 * @return int
	 */
	public function delete( string $key ): int;

	/**
	 * Delete records matching conditions.
	 *
	 * @param array $wheres Where conditions.
	 *
	 * @return int
	 */
	public function delete_many( array $wheres ): int;
}
