<?php

namespace Tribe\Project\Syndicate\Admin;

use Tribe\Project\Syndicate\Admin\Contracts\Display;
use Tribe\Project\Syndicate\Blog;

class Posts extends Display {

	/**
	 * @param $actions
	 * @param $post
	 *
	 * @return mixed
	 * @filter post_row_actions
	 */
	public function post_row_actions( $actions, $post ) {
		if ( is_main_site() ) {
			return $actions;
		}

		$copier = new Copier( $this->blog );
		$copy_actions = $copier->do_copy_links( $post );

		return array_merge( $copy_actions, $actions );
	}

	/**
	 * @param $caps
	 * @param $cap
	 * @param $user_id
	 * @param $args
	 *
	 * @return array
	 * @filter map_meta_cap
	 */
	public function disable_edit( $caps, $cap, $user_id, $args ) {
		if ( is_main_site() ) {
			return $caps;
		}

		if ( isset( $args[0] ) && ! $this->object_in_blog( $args[0] ) && in_array( $cap, $this->cap_whitelist() ) ) {
			$caps[] = 'do_not_allow';
		}
		return $caps;
	}

	private function cap_whitelist() {
		static $capabilities;
		if ( count( $capabilities ) ) {
			return $capabilities;
		}

		$post_types = get_post_types( [], 'objects' );
		$post_caps  = [];
		foreach ( $post_types as $post_type ) {
			$post_caps = array_unique( array_merge( $post_caps, array_values( (array) $post_type->cap ) ) );
		}

		$taxonomies = get_taxonomies( [], 'objects' );
		$tax_caps = [];
		foreach ( $taxonomies as $taxonomy ) {
			$tax_caps = array_unique( array_merge( $tax_caps, array_values( (array) $taxonomy->cap ) ) );
		}

		$capabilities = array_merge( $post_caps, $tax_caps );
		return $capabilities;
	}

}
