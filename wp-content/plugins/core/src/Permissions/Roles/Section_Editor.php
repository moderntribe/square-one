<?php


namespace Tribe\Project\Permissions\Roles;

class Section_Editor extends Section_Role {
	const NAME = 'section_editor';

	public function get_label() {
		return __( 'Section Editor', 'tribe' );
	}

	public function general_capabilities(): array {
		$user_caps = parent::general_capabilities();

		$user_caps[ 'edit_nav_menus' ] = true;

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
		$user_caps        = [];
		$post_type_object = get_post_type_object( $post_type );
		if ( empty( $post_type_object ) ) {
			return $user_caps;
		}
		$user_caps[ $post_type_object->cap->create_posts ] = true;
		$user_caps[ $post_type_object->cap->edit_posts ]   = true;
		$user_caps[ $post_type_object->cap->delete_posts ] = true;

		$user_caps[ $post_type_object->cap->publish_posts ]          = true;
		$user_caps[ $post_type_object->cap->edit_published_posts ]   = true;
		$user_caps[ $post_type_object->cap->delete_published_posts ] = true;

		// if a user can edit posts, he should be able to upload images
		$user_caps[ 'upload_files' ] = true;

		return $user_caps;
	}

	public function post_capabilities( $post_id ): array {
		$post_type = get_post_type( $post_id );
		if ( ! in_array( $post_type, $this->post_types ) ) {
			return [];
		}
		$post_type_object = get_post_type_object( $post_type );

		$user_caps[ $post_type_object->cap->edit_others_posts ]   = true;
		$user_caps[ $post_type_object->cap->delete_others_posts ] = true;

		$user_caps[ $post_type_object->cap->read_private_posts ]   = true;
		$user_caps[ $post_type_object->cap->edit_private_posts ]   = true;
		$user_caps[ $post_type_object->cap->delete_private_posts ] = true;

		$user_caps[ $post_type_object->cap->delete_published_posts ] = true;

		return $user_caps;
	}

}