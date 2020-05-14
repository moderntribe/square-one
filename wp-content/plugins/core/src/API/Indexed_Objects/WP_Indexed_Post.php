<?php
/**
 * The indexed WP_Post object.
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Indexed_Objects;

/**
 * Class WP_Indexed_Post.
 */
class WP_Indexed_Post implements Indexable {

	/**
	 * The object type, usually corresponding to the WP_Post->post_type.
	 */
	public const OBJECT_TYPE = 'post';

	/**
	 * WP_Post constructor.
	 *
	 * @param \WP_Post $post
	 */
	public function __construct( \WP_Post $post ) {
	}
}
