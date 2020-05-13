<?php
/**
 * The indexed WP_Post object.
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Indexed_Objects;

/**
 * Class WP_Post.
 */
class WP_Post implements Indexable {

	/**
	 * The object type, usually corresponding to the WP_Post->post_type.
	 */
	public const OBJECT_TYPE = 'post';
}
