<?php


namespace Tribe\Project\Permissions\Users;


use Tribe\Project\Permissions\Taxonomies\Section\Section;
use Tribe\Project\Permissions\Schema\User_Section_Table;

class User {
	/** @var \WP_User */
	private $wp_user;

	public function __construct( \WP_User $wp_user ) {
		$this->wp_user = $wp_user;
	}

	/**
	 * Get the names of all roles associated with
	 * this user for any section
	 *
	 * @return string[]
	 */
	public function all_roles(): array {
		/** @var \wpdb $wpdb */
		global $wpdb;
		$sql = "SELECT role FROM {$wpdb->{User_Section_Table::NAME}} WHERE user_id=%d";
		$sql = $wpdb->prepare( $sql, $this->wp_user->ID );

		return $wpdb->get_col( $sql );
	}

	/**
	 * @param string $role
	 *
	 * @return int[]
	 */
	public function sections( $role = '' ): array {
		/** @var \wpdb $wpdb */
		global $wpdb;
		$sql = "SELECT section_id FROM {$wpdb->{User_Section_Table::NAME}} WHERE user_id=%d";
		$params = [ $this->wp_user->ID ];
		if ( ! empty( $role ) ) {
			$sql .= " AND role=%s";
			$params[] = $role;
		}
		$sql = $wpdb->prepare( $sql, ...$params );

		return array_map( 'intval', $wpdb->get_col( $sql ) );
	}

	/**
	 * Get the role for this user for the given section.
	 *
	 * @param int $section_id
	 *
	 * @return string
	 */
	public function get_role( $section_id ): string {
		if ( ! $section_id ) {
			return '';
		}

		$section = Section::factory( $section_id );

		return $section->get_role( $this->wp_user );
	}
}