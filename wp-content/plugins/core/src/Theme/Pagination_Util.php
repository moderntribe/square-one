<?php

namespace Tribe\Project\Theme;

class Pagination_Util {

	/**
	 * @var \WP_Query $query
	 */
	protected $query;

	public function __construct( \WP_Query $query = null ) {
		global $wp_query;

		$this->query = $query ?? $wp_query;
	}

	/**
	 * Returns an array of pagination number links.
	 *
	 * @param bool $links_offset    - the max # of links to show on either side of the current page. False for all pages.
	 * @param bool $show_next_prev  - show links for next/prev page.
	 * @param bool $show_first_last - show links for first/last page.
	 * @param bool $show_ellipses   - show ellipses when pages exist beyond offset on either side of current page.
	 *
	 * @return array
	 */
	public function numbers( $links_offset = false, $show_next_prev = true, $show_first_last = true, $show_ellipses = true ) {
		$links  = [];
		$values = [];

		// Stop execution if there's only 1 page
		if ( $this->query->max_num_pages <= 1 ) {
			return [];
		}

		$paged    = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		$max      = (int) $this->query->max_num_pages;
		$is_first = $paged == 1;
		$is_last  = $paged == $max;

		// Only add links on either side of current if we're not loading all the pages at once. Generate links for each applicable number we need to show.
		if ( $links_offset ) {

			$links[] = $paged;

			for ( $i = max( 1, $paged - $links_offset ); $i < $paged; $i ++ ) {
				$links[] = $i;
			}

			for ( $i = $paged + 1; $i <= $paged + $links_offset && $i <= $max; $i ++ ) {
				$links[] = $i;
			}
		} else {
			for ( $i = 1; $i <= $max; $i ++ ) {
				$links[] = $i;
			}
		}

		// If we're showing first/last links, add the link to the first page if we're not already on it.
		if ( $show_first_last && ! $is_first ) {
			$values[] = $this->get_link_array( get_pagenum_link( 1 ), '<<', [ 'c-pagination__link c-pagination__link--first' ] );
		}

		// If we're showing previous/next links, add the link to the previous page if we're not already on it.
		if ( $show_next_prev && ! $is_first ) {
			$values[] = $this->get_link_array( get_pagenum_link( $paged - 1 ), '<', [ 'c-pagination__link c-pagination__link--prev' ], false, false, true );
		}

		// If we're showing ellipses, add them here as long as they're necessary.
		if ( $show_ellipses && ! $is_first && ! in_array( 1, $links ) ) {
			$values[] = $this->get_link_array( '#', '...', [ 'c-pagination__link c-pagination__link--ellipses' ] );
		}

		// Sort the $links in case they're out of numeric order, then add any that we've generated so far to the return values.
		sort( $links );
		foreach ( $links as $link ) {
			$active   = $paged == $link;
			$values[] = $this->get_link_array( get_pagenum_link( $link ), $link, [], $active );
		}

		// If we're showing ellipses, add them here as long as they're necessary.
		if ( $show_ellipses && ! in_array( $max, $links ) ) {
			$values[] = $this->get_link_array( '#', '...', [ 'c-pagination__link c-pagination__link--ellipses' ] );
		}

		// If we're showing previous/next links, add the link to the next page if we're not already the last page.
		if ( $show_next_prev && ! $is_last ) {
			$values[] = $this->get_link_array( get_pagenum_link( $paged + 1 ), '>', [ 'c-pagination__link c-pagination__link--next' ], false, true );
		}

		// If we're showing first/last links, add the link to the last page if we're not already on it.
		if ( $show_first_last && ! $is_last ) {
			$values[] = $this->get_link_array( get_pagenum_link( $max ), '>>', [ 'c-pagination__link c-pagination__link--last' ] );
		}

		return $values;
	}

	/**
	 * Get the array with proper values for a link.
	 *
	 * @param string $url
	 * @param string $label
	 * @param array  $classes
	 * @param bool   $active
	 * @param bool   $next
	 * @param bool   $prev
	 *
	 * @return array
	 */
	protected function get_link_array( string $url, string $label, array $classes = [], bool $active = false, bool $next = false, bool $prev = false  ) {
		return [
			'url'     => $url,
			'label'   => $label,
			'active'  => $active,
			'next'    => $next,
			'prev'    => $prev,
			'classes' => $classes,
		];
	}

}