<?php


namespace Tribe\Project\Permissions\Capabilities;


use Tribe\Project\Permissions\Object_Meta\Default_Section;
use Tribe\Project\Permissions\Section_Switcher;
use Tribe\Project\Permissions\Taxonomies\Section\Section;
use Tribe\Project\Permissions\Users\User;

class Posts_Admin {
	/**
	 * @param \WP_Query $query
	 *
	 * @return void
	 * @action pre_get_posts after load-edit.php
	 */
	public function filter_other_sections_from_list_tables( $query ) {
		if ( ! $query->is_main_query() ) {
			return;
		}
		$current = $this->get_current_section();
		if ( empty( $current ) ) {
			return;
		}
		$term = get_term( $current );
		if ( empty( $term ) || is_wp_error( $term ) ) {
			return;
		}
		$query->set( Section::NAME, $term->slug );
	}

	/**
	 * Get the ID of the currently selected section
	 *
	 * @return int
	 */
	private function get_current_section() {
		$wp_user = wp_get_current_user();
		$default = (int) get_option( Default_Section::TERM_ID, 0 );
		$current = (int) ( $wp_user->exists() ? get_user_option( Section_Switcher::FIELD_NAME, $wp_user->ID ) : 0 );

		// check if the user is in the section first (unless admin)
		if ( $wp_user->has_cap( 'edit_others_posts' ) ) {
			return ( $current ?: $default );
		}
		$user         = new User( $wp_user );
		$user_sctions = $user->sections();
		if ( ! in_array( $current, $user_sctions ) ) {
			return $default;
		}

		return $current ?: $default;
	}
}