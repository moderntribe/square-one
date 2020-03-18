<?php


namespace Tribe\Project\P2P;

class Panel_Search_Filters {

	/**
	 * @return void
	 *
	 * @action wp_ajax_posts-field-p2p-options-search 0
	 */
	public function set_p2p_search_filters() {
		add_action( 'pre_get_posts', [ $this, 'convert_global_search_to_title_search' ], 10, 1 );
		add_filter( 'posts_where', [ $this, 'add_title_search_to_where_clause' ], 15, 2 );
	}

	/**
	 * @param \WP_Query $query
	 *
	 * @return void
	 */
	public function convert_global_search_to_title_search( $query ) {
		if ( !$query->get( 'suppress_filters' ) && $search = $query->get( 's' ) ) {
			$query->set( 'title_only_search', $search );
			unset( $query->query_vars['s'] );
		}
	}

	/**
	 * @param string $clause
	 * @param \WP_Query $query
	 *
	 * @return string
	 */
	public function add_title_search_to_where_clause( $clause, $query ) {
		$search = $query->get( 'title_only_search' );
		if ( $search ) {
			/** @var \wpdb $wpdb */
			global $wpdb;
			$like = '%' . $wpdb->esc_like( $search ) . '%';
			$clause .= $wpdb->prepare( " AND $wpdb->posts.post_title LIKE %s", $like );
		}
		return $clause;
	}
}
