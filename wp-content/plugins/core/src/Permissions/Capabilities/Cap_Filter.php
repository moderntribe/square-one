<?php


namespace Tribe\Project\Permissions\Capabilities;

use Tribe\Project\Permissions\Roles\Role_Collection;
use Tribe\Project\Permissions\Roles\Section_Role_Interface;
use Tribe\Project\Permissions\Taxonomies\Section\Section;
use Tribe\Project\Permissions\Users\User;

class Cap_Filter {
	/** @var Role_Collection */
	private $roles;

	/**
	 * @var array General capabilities that have been initialized for each user
	 */
	private $users = [];

	private $single_post_caps = [
		'edit_post',
		'edit_page',
		'read_post',
		'read_page',
		'publish_post',
		'delete_post',
		'delete_page',
	];

	public function __construct( Role_Collection $roles ) {
		$this->roles = $roles;
	}

	/**
	 * Filter the user's capabilities
	 *
	 * @param array    $user_caps
	 * @param array    $required_caps
	 * @param array    $args
	 * @param \WP_User $user
	 *
	 * @return array
	 * @filter user_has_cap
	 */
	public function set_user_caps( $user_caps, $required_caps, $args, $user ) {
		$requested_cap = $args[ 0 ];
		if ( ! $user->exists() ) {
			return $user_caps;
		}

		$taxonomy = get_taxonomy( Section::NAME );

		if ( ! $taxonomy ) {
			// if the taxonomy is not yet registered, we have no
			// business filtering any capabilities
			return $user_caps;
		}

		$this->init_capabilities( $user );

		// Assign any global caps relevant to the required caps
		$user_caps = array_merge( $user_caps, $this->users[ $user->ID ] );

		// Assign any post-specific caps relevant to the required caps
		if ( in_array( $requested_cap, $this->single_post_caps ) ) {
			$post_id   = $args[ 2 ];
			$role      = $this->get_role_for_post( $post_id, $user );
			$post_caps = $role->post_capabilities( $post_id );

			foreach ( $required_caps as $cap ) {
				if ( ! empty( $post_caps[ $cap ] ) ) {
					$user_caps[ $cap ] = true;
				}
			}
		}

		// Special case: editing a Section term
		if ( $requested_cap === 'edit_term' ) {
			$term = get_term( $args[ 2 ] );
			if ( $term->taxonomy === Section::NAME ) {
				$role = $this->get_role_for_section( $term->term_id, $user );
				if ( $role->can_edit_section() ) {
					$user_caps[ $taxonomy->cap->edit_terms ] = true;
				}
			}
		}

		return $user_caps;
	}

	/**
	 * Set up the tribe_section_capabilities property on the WP_User
	 * object. This will hold all of the user's capabilities that
	 * are not specific to individual posts.
	 *
	 * @param \WP_User $user
	 *
	 * @return void
	 */
	private function init_capabilities( \WP_User $user ) {
		if ( array_key_exists( $user->ID, $this->users ) ) {
			return; // the user is already initialized
		}

		$user_caps = [];
		$taxonomy  = get_taxonomy( Section::NAME );

		if ( in_array( 'administrator', $user->roles ) ) {
			// administrators get full caps for the taxonomy
			$user_caps[ $taxonomy->cap->manage_terms ] = true;
			$user_caps[ $taxonomy->cap->edit_terms ]   = true;
			$user_caps[ $taxonomy->cap->delete_terms ] = true;
			$user_caps[ $taxonomy->cap->assign_terms ] = true;
		}

		foreach ( $this->get_all_roles( $user ) as $role ) {
			$user_caps = array_merge( $user_caps, $role->general_capabilities() );
		}

		$this->users[ $user->ID ] = $user_caps;
	}

	/**
	 * @param \WP_User $user
	 *
	 * @return Section_Role_Interface[]
	 */
	private function get_all_roles( \WP_User $user ) {
		$section_user = new User( $user );
		$roles        = $section_user->all_roles();

		return array_map( [ $this->roles, 'get_role' ], $roles );
	}

	/**
	 * @param int      $post_id
	 * @param \WP_User $user
	 *
	 * @return Section_Role_Interface
	 */
	private function get_role_for_post( $post_id, $user ) {
		$section_id = $this->get_post_section_id( $post_id );

		return $this->get_role_for_section( $section_id, $user );
	}

	private function get_post_section_id( $post_id ) {
		$terms = wp_get_post_terms( $post_id, Section::NAME );
		if ( count( $terms ) !== 1 ) {
			return null;
		}

		return (int) reset( $terms )->term_id;
	}

	private function get_role_for_section( $section_id, $user ) {
		$section_user = new User( $user );
		$role_name    = $section_user->get_role( $section_id );

		return $this->roles->get_role( $role_name );
	}
}