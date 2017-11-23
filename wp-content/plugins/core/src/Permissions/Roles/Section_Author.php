<?php


namespace Tribe\Project\Permissions\Roles;


class Section_Author extends Section_Role {
	const NAME = 'section_author';

	public function get_label() {
		return __( 'Section Author', 'tribe' );
	}

	/**
	 * Get the caps associated with a particular post type
	 *
	 * @param string $post_type
	 *
	 * @return array
	 */
	protected function get_post_type_user_caps( $post_type ): array {
		$user_caps        = [];
		$post_type_object = get_post_type_object( $post_type );
		if ( empty( $post_type_object ) ) {
			return $user_caps;
		}
		$user_caps[ $post_type_object->cap->create_posts ]           = true;
		$user_caps[ $post_type_object->cap->edit_posts ]             = true;
		$user_caps[ $post_type_object->cap->delete_posts ]           = true;
		$user_caps[ $post_type_object->cap->publish_posts ]          = true;
		$user_caps[ $post_type_object->cap->edit_published_posts ]   = true;
		$user_caps[ $post_type_object->cap->delete_published_posts ] = true;

		// if a user can edit posts, he should be able to upload images
		$user_caps[ 'upload_files' ] = true;

		return $user_caps;
	}

	public function post_capabilities( $post_id ): array {
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
}