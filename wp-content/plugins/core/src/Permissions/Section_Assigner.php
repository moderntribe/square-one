<?php


namespace Tribe\Project\Permissions;

use Tribe\Project\Permissions\Object_Meta\Default_Section;
use Tribe\Project\Permissions\Object_Meta\Menu_Sections;
use Tribe\Project\Permissions\Taxonomies\Section\Section;
use Tribe\Project\Permissions\Users\User;

/**
 * Class Section_Assigner
 *
 * Automatically assigns sections to all content
 */
class Section_Assigner {
	/**
	 * @param int      $post_id
	 * @param \WP_Post $post
	 *
	 * @return void
	 * @action save_post
	 */
	public function assign_section_to_post( $post_id, $post ) {
		$terms = get_the_terms( $post_id, Section::NAME );
		if ( ! empty( $terms ) ) {
			return;
		}
		$current = $this->get_current_section();
		if ( empty( $current ) ) {
			return;
		}
		wp_set_object_terms( $post_id, [ $current ], Section::NAME );
	}

	/**
	 * @param int $menu_id
	 *
	 * @return void
	 * @action wp_create_nav_menu
	 * @action wp_update_nav_menu
	 */
	public function assign_section_to_menu( $menu_id ) {
		$sections = get_term_meta( $menu_id, Menu_Sections::SECTIONS, true );
		if ( ! empty( $sections ) ) {
			return;
		}
		$current = $this->get_current_section();
		if ( empty( $current ) ) {
			return;
		}
		update_term_meta( $menu_id, Menu_Sections::SECTIONS, [ $current ] );
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