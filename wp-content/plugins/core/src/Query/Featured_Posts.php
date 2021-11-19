<?php declare(strict_types=1);

namespace Tribe\Project\Query;

use Tribe\Project\Object_Meta\Post_Archive_Featured_Settings;
use WP_Query;

class Featured_Posts {

	/**
	 * If we have featured posts that can be configured on the `wp-admin/edit.php?page=edit-php-settings` page,
	 * this method, when used with the 'pre_get_post' action, will remove featured posts
	 * from the main query to prevent posts from showing twice if we have featured posts on main blog page.
	 *
	 * @hook https://developer.wordpress.org/reference/hooks/pre_get_post/
	 */
	public function remove_featured_posts_from_main_query( WP_Query $query ): WP_Query {

		if ( ! is_home() || ! $query->is_main_query() ) {
			return $query;
		}

		$featured_post_objects_array = get_field( Post_Archive_Featured_Settings::FEATURED_POSTS, 'option' );
		$featured_post_ids_array     = [];

		if ( empty( $featured_post_objects_array ) ) {
			return $query;
		}
		/** @var $post \WP_Post */
		foreach ( $featured_post_objects_array as $post ) {
			$featured_post_ids_array[] = $post->ID;
		}

		$query->set( 'post__not_in', $featured_post_ids_array );

		return $query;
	}

}
