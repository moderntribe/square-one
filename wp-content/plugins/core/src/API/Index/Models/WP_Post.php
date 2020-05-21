<?php
/**
 * The indexed WP_Post object.
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Index\Models;

/**
 * Class Indexed_WP_Post.
 */
class WP_Post extends Indexable {
	/**
	 * @var int The post ID.
	 */
	public $ID;

	/**
	 * @var string The post thumbnail URL.
	 */
	public $thumb;

	/**
	 * WP_Post constructor.
	 *
	 * @param \WP_Post $post
	 */
	public function __construct( \WP_Post $post ) {
		$this->ID    = $post->ID;
		$this->thumb = (string) get_the_post_thumbnail_url( $post->ID );
	}
}
