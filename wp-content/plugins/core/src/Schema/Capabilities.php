<?php


namespace Tribe\Project\Schema;

/**
 * Class Capabilities
 *
 * @package Tribe\Project\Schools
 *
 * A utililty class for registering capabilities for a post type
 */
class Capabilities {
	private $caps = [
		'editor'      => [
			'read',
			'read_private_posts',
			'create_posts',
			'edit_posts',
			'edit_others_posts',
			'edit_private_posts',
			'edit_published_posts',
			'delete_posts',
			'delete_others_posts',
			'delete_private_posts',
			'delete_published_posts',
			'publish_posts',
		],
		'author'      => [
			'read',
			'create_posts',
			'edit_posts',
			'edit_published_posts',
			'delete_posts',
			'delete_published_posts',
			'publish_posts',
		],
		'contributor' => [
			'read',
			'create_posts',
			'edit_posts',
			'delete_posts',
		],
		'subscriber'  => [
			'read',
		],
	];

	/**
	 * Register capabilities to a role for a post type
	 *
	 * @param string $post_type The name of a registered post type
	 * @param string $role_id   The name of a registered role
	 * @param string $level     The access level the user should have. One of 'subscriber', 'contributor', 'author', or
	 *                          'editor'
	 * @return bool
	 */
	public function register_post_type_caps( $post_type, $role_id, $level = 'editor' ) {
		if ( ! isset( $this->caps[ $level ] ) ) {
			return false;
		}

		$role = get_role( $role_id );
		if ( ! $role ) {
			return false;
		}

		$pto = get_post_type_object( $post_type );
		if ( ! $pto ) {
			_deprecated_argument( __FUNCTION__, '2016-09-02', 'First argument should be a registered post type, rather than the plural capability prefix.' );
			return $this->register_post_type_caps_with_prefix( $post_type, $role, $level );
		}

		foreach ( $this->caps[ $level ] as $cap ) {
			$role->add_cap( $pto->cap->{$cap} );
		}

		return true;
	}

	/**
	 * Provided for backwards compatibility. Registers post type capabilities
	 * using the plural post type slug as a suffix.
	 *
	 * Not recommended, because it does not take into account custom capabilities
	 * set for the post type when it is registered.
	 *
	 * @param string   $post_type_suffix The plural form of the post type's capability_type
	 * @param \WP_Role $role             The role object
	 * @param string   $level            The access level the user should have. One of 'subscriber', 'contributor',
	 *                                   'author', or 'editor'
	 * @return bool
	 */
	private function register_post_type_caps_with_prefix( $post_type_suffix, $role, $level = 'editor' ) {
		// argument validation handled above in self::register_post_type_caps()
		foreach ( $this->caps[ $level ] as $cap ) {
			$prefix = str_replace( '_posts', '', $cap );
			$role->add_cap( $prefix . '_' . $post_type_suffix );
		}
		return true;
	}
} 