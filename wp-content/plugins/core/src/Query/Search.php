<?php declare(strict_types=1);

namespace Tribe\Project\Query;

class Search {

	/**
	 * Prevents the query for the WP Core search from running when there is no search term `?s=`,
	 * but still returns the search route (page title, template, etc).
	 *
	 * @param string $request
	 * @param \WP_Query $query
	 *
	 * @hook https://developer.wordpress.org/reference/hooks/posts_request/
	 */
	public function force_load_search_template( string $request, \WP_Query $query ): string {
		//This basically prevents the query for search but still provides the benefit of not
		//sending back a 404 and still provides the page title, etc.
		if ( $query->is_search() && ! is_admin() && empty( $query->get( 's' ) ) ) {
			/* prevent SELECT FOUND_ROWS() query */
			$query->query_vars['no_found_rows'] = true;

			/* prevent post term and meta cache update queries */
			$query->query_vars['cache_results'] = false;

			return '';
		}

		return $request;
	}

}
