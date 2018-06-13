<?php


namespace Tribe\Project\ACF;

use Tribe\Project\Object_Meta\Meta_Group;

abstract class ACF_Meta_Group extends Meta_Group {

	public function register_group() {
		$config = $this->get_group_config();
		acf_add_local_field_group( $config );
	}


	/**
	 * Base implementation that uses get_field() for all registered keys
	 *
	 * If you have calculated/aggregated keys that don't match directly
	 * to a meta, you'll need to override this method on the child class.
	 *
	 * @param int    $post_id
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function get_value( $post_id, $key ) {
		return in_array( $key, $this->get_keys(), true ) ? get_field( $key, $post_id ) : null;
	}

	/**
	 * @return array The ACF config array for the field group
	 */
	abstract protected function get_group_config();
}