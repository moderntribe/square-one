<?php
/**
 * The API class that handles integrations with WordPress.
 *
 * @package    SquareOne
 * @subpackage API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Index\Models;

use Tribe\Project\DB\Exceptions\IndexableNotFoundException;

/**
 * Class Indexable_Factory.
 */
class Indexable_Factory {
	/**
	 * Creates the right indexable object.
	 *
	 * @param object $object The object we are indexing.
	 *
	 * @return Indexable
	 * @throws IndexableNotFoundException
	 */
	public function make( object $object ): Indexable {
		if ( $object instanceof \WP_Post ) {
			return new WP_Post( $object );
		}

		throw new IndexableNotFoundException( get_class( $object ) );
	}
}
