<?php

namespace Tribe\Project\Post_Meta;

/**
 * Class Meta_Group
 *
 * A container for one or more post meta fields.
 *
 * It is the responsibility of instances of this class to
 *  - register meta boxes/fields with WP (probably via a lib like ACF or CMB2)
 *  - return a list of keys for which this group is willing to handle finding a value
 *  - return the appropriate value when one of said keys is requested
 */
abstract class Meta_Group {
	const NAME = '';

	protected $post_types = [ ];

	/**
	 * Meta_Group constructor.
	 *
	 * @param array $post_types The post types the meta group applies to
	 */
	public function __construct( array $post_types ) {
		$this->post_types = $post_types;
	}

	/**
	 * @return array The post types this meta group applies to
	 */
	public function get_post_types() {
		return $this->post_types;
	}

	/**
	 * @return array The meta keys that this field will handle.
	 *               While these will probably directly correspond
	 *               to meta keys in the database, there is no
	 *               guaranteed, as the key may correspond to
	 *               a computed/aggregate value.
	 */
	abstract public function get_keys();

	/**
	 * @param int    $post_id
	 * @param string $key
	 * @return mixed The value for the given key
	 */
	abstract public function get_value( $post_id, $key );

	/**
	 * Set up hooks with WordPress to register meta boxes and fields
	 *
	 * @return void
	 */
	abstract public function hook();
}