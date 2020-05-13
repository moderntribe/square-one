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
	 * @param string $object_type The type of object we are indexing.
	 *
	 * @return Indexable
	 * @throws IndexableNotFoundException
	 */
	public function make( string $object_type ): Indexable {
		switch ( $object_type ) {
			case WP_Post::OBJECT_TYPE:
				return new WP_Post();
		}

		throw new IndexableNotFoundException( $object_type );
	}
}
