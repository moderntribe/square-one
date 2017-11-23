<?php


namespace Tribe\Project\Permissions\Roles;


interface Section_Role_Interface {


	public function get_name();

	public function get_label();

	/**
	 * Get the capabilities associated with this role
	 * that apply globally (i.e., are not tied to a
	 * specific post).
	 *
	 * @return array Capabilties as keys, boolean (usually `true`) as value
	 */
	public function general_capabilities(): array;

	/**
	 * Get the capabilities associated with this role
	 * that apply to a specific post, assuming that the
	 * post is associated with the appropriate section.
	 *
	 * @param int $post_id
	 *
	 * @return array Capabilties as keys, boolean (usually `true`) as value
	 */
	public function post_capabilities( $post_id ): array;

	/**
	 * Indicates if this role allows a user to edit the section
	 * in which the user has the role
	 *
	 * @return bool
	 */
	public function can_edit_section(): bool;
}