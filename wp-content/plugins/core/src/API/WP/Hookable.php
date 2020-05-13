<?php


namespace Tribe\Project\API\WP;


interface Hookable {
	/**
	 * Save an indexed version of the post in our index.
	 *
	 * @param object $object The object WordPress is saving.
	 *
	 * @action save_post
	 */
	public function save_post( object $object ): void;

	/**
	 * Delete a post from the index.
	 *
	 * @param object $object The object WordPress is saving.
	 *
	 * @action delete_post
	 */
	public function delete_post( object $object ): void;
}
