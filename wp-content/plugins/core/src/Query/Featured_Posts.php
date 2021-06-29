<?php declare(strict_types=1);

namespace Tribe\Project\Query;

use Tribe\Project\Taxonomies\Featured\Featured;
use WP_Query;

class Featured_Posts {

	/**
	 * If we have featured posts (A default registered post taxonomy and object meta in the form of a radio toggle),
	 * this method, when used with the 'pre_get_post' action, will remove featured posts
	 * from the main query to prevent posts from showing twice if we have featured posts on the index and archive pages.
	 *
	 * @hook https://developer.wordpress.org/reference/hooks/pre_get_post/
	 */
	public function remove_featured_posts_from_main_query( WP_Query $query ): WP_Query {

		if ( ! $query->is_main_query() ) { // TODO: need some better rules here
			return $query;
		}

		$query->set(
			'tax_query',
			[
				[
					'taxonomy' => Featured::NAME,
					'terms'    => ['yes'],
					'field'    => 'slug',
					'operator' => 'NOT IN',
				],
			 ]
		);

		return $query;
	}

}
