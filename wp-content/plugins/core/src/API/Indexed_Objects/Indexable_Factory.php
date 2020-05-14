<?php
/**
 * The API class that handles integrations with WordPress.
 *
 * @package    SquareOne
 * @subpackage API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Indexed_Objects;

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
		switch ( $object ) {
			case WP_Indexed_Post::OBJECT_TYPE:
				return new WP_Indexed_Post( $object_type );
		}

		throw new IndexableNotFoundException( $object_type );
	}
}
