<?php
/**
 * Repository base class.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Repository;

use Tribe\Project\DB\Models\Storable;

/**
 * Class Repository.
 */
abstract class Repository implements Persistent {

	/**
	 * @inheritDoc
	 */
	public function get_model_classname(): string {
		return $this->storable->get_classname();
	}

	/**
	 * @inheritDoc
	 */
	public function get_slug(): string {
		return $this->slug;
	}

	/**
	 * @inheritDoc
	 */
	public function get_prefixed_name(): string {
		return $this->storable::get_table_name();
	}

	/**
	 * @inheritDoc
	 */
	abstract public function insert( Storable $record ): int;

	/**
	 * @inheritDoc
	 */
	abstract public function insert_many( array $records ): int;

	/**
	 * @inheritDoc
	 */
	abstract public function get( string $key ): Storable;

	/**
	 * @inheritDoc
	 */
	abstract public function get_many( array $wheres = [] ): array;

	/**
	 * @inheritDoc
	 */
	abstract public function update( Storable $storable, ?Storable $original = null ): int;

	/**
	 * @inheritDoc
	 */
	abstract public function update_field( string $key, $value, array $wheres = [] ): int;

	/**
	 * @inheritDoc
	 */
	abstract public function update_fields( array $key_value_pairs, array $wheres = [] ): int;

	/**
	 * @inheritDoc
	 */
	abstract public function delete( string $key ): int;

	/**
	 * @inheritDoc
	 */
	abstract public function delete_many( array $wheres ): int;
}
