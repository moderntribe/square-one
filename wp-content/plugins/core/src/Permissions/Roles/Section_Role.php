<?php


namespace Tribe\Project\Permissions\Roles;

abstract class Section_Role implements Section_Role_Interface {
	const NAME = '';

	/**
	 * @var string[] Post types that this role can access
	 */
	protected $post_types = [];

	/**
	 * @var array Taxonomies that this role can access
	 */
	protected $taxonomies = [];

	public function __construct( array $post_types, array $taxonomies ) {
		if ( static::NAME === '' ) {
			throw new \LogicException( 'Section Role class requires a NAME constant' );
		}

		$this->post_types = $post_types;
		$this->taxonomies = $taxonomies;
	}

	public function get_name() {
		return static::NAME;
	}

	/**
	 * Get the capabilities associated with this role
	 * that apply to a specific post, assuming that the
	 * post is associated with the appropriate section.
	 *
	 * @param int $post_id
	 *
	 * @return array Capabilties as keys, boolean (usually `true`) as value
	 */
	public function post_capabilities( $post_id ): array {
		return [];
	}

	/**
	 * Get the capabilities associated with this role
	 * that apply globally (i.e., are not tied to a
	 * specific post).
	 *
	 * @return array Capabilties as keys, boolean (usually `true`) as value
	 */
	public function general_capabilities(): array {
		$post_type_caps = array_merge( ...array_map( [ $this, 'get_post_type_user_caps', ], $this->post_types ) );
		$taxonomy_caps  = array_merge( ...array_map( [ $this, 'get_taxonomy_user_caps' ], $this->taxonomies ) );

		$user_caps = array_merge( $post_type_caps, $taxonomy_caps );

		return $user_caps;
	}


	/**
	 * Get the caps associated with a particular post type
	 *
	 * @param string $post_type
	 *
	 * @return array
	 */
	protected function get_post_type_user_caps( $post_type ): array {
		return [];
	}

	/**
	 * Get the caps associated with a particular taxonomy
	 *
	 * @param string $taxonomy
	 *
	 * @return array
	 */
	protected function get_taxonomy_user_caps( $taxonomy ): array {
		$user_caps       = [];
		$taxonomy_object = get_taxonomy( $taxonomy );
		if ( empty( $taxonomy_object ) ) {
			return $user_caps;
		}
		$user_caps[ $taxonomy_object->cap->assign_terms ] = true;

		return $user_caps;
	}

	/**
	 * Indicates if this role allows a user to edit the section
	 * in which the user has the role
	 *
	 * @return bool
	 */
	public function can_edit_section(): bool {
		return false;
	}

}