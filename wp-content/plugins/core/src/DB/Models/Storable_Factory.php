<?php
/**
 * Creates Storable objects.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Models;

/**
 * Class Storable_Factory.
 */
class Storable_Factory {
	/**
	 * Create a Storable.
	 *
	 * @param string $classname
	 *
	 * @return Storable
	 */
	public function make( string $classname ): Storable {
		if ( class_exists( $classname ) ) {
			return new $classname();
		}
	}

	/**
	 * @param string $classname
	 * @param array  $record
	 *
	 * @return Storable
	 */
	public function make_and_hydrate( string $classname, array $record ): Storable {
		$storable = $this->make( $classname );
		$storable->hydrate( $record );

		return $storable;
	}
}
