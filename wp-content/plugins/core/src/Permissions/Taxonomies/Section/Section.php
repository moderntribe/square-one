<?php


namespace Tribe\Project\Permissions\Taxonomies\Section;


use Tribe\Libs\Taxonomy\Term_Object;

class Section extends Term_Object {
	const NAME    = 'section';

	const DEFAULT = 'home';

	/**
	 * Get the user IDs of all users assigned the given
	 * role for this section
	 *
	 * @param string $role
	 *
	 * @return int[]
	 */
	public function get_users( string $role ): array {
		/** @var \wpdb $wpdb */
		global $wpdb;
		$sql = "SELECT user_id FROM {$wpdb->user_section} WHERE section_id=%d AND role=%s";
		$sql = $wpdb->prepare( $sql, $this->term_id, $role );
		$ids = $wpdb->get_col( $sql );

		return array_map( 'intval', $ids );
	}

	/**
	 * Assign the user the given role for this section.
	 * If the user already has a role, it will be replaced.
	 *
	 * @param \WP_User $user
	 * @param string   $role
	 *
	 * @return void
	 */
	public function set_role( \WP_User $user, $role ) {
		/** @var \wpdb $wpdb */
		global $wpdb;
		$found_role = $this->get_role( $user );

		if ( $found_role === $role ) {
			return; // nothing to do
		}

		if ( $found_role ) {
			$wpdb->update(
				$wpdb->user_section,
				[ 'role' => $role ],
				[ 'section_id' => $this->term_id, 'user_id' => $user->ID ],
				[ '%s' ],
				[ '%d', '%d' ]
			);
		} else {
			$wpdb->insert(
				$wpdb->user_section,
				[ 'section_id' => $this->term_id, 'user_id' => $user->ID, 'role' => $role ],
				[ '%d', '%d', '%s' ]
			);
		}

		do_action( 'tribe/permissions/set_role', $user, $this->term_id, $role );
	}

	/**
	 * Remove the user from this section
	 *
	 * @param \WP_User $user
	 *
	 * @return void
	 */
	public function delete_role( \WP_User $user ) {
		/** @var \wpdb $wpdb */
		global $wpdb;

		$wpdb->delete(
			$wpdb->user_section,
			[ 'section_id' => $this->term_id, 'user_id' => $user->ID ],
			[ '%d', '%d' ]
		);
		do_action( 'tribe/permissions/delete_role', $user, $this->term_id );
	}

	/**
	 * Get the role the user has for this section.
	 *
	 * @param \WP_User $user
	 *
	 * @return string The name of the role, or null if none found
	 */
	public function get_role( \WP_User $user ): string {
		/** @var \wpdb $wpdb */
		global $wpdb;
		$sql = "SELECT role FROM {$wpdb->user_section} WHERE section_id=%d AND user_id=%d";
		$sql = $wpdb->prepare( $sql, $this->term_id, $user->ID );

		return (string) $wpdb->get_var( $sql );
	}
}