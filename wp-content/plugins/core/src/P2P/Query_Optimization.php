<?php


namespace Tribe\Project\P2P;

class Query_Optimization {
	public function hook() {
		add_action( 'p2p_init', [ $this, 'p2p_init' ], 10, 0 );  // after _p2p_load at 9
	}

	public function p2p_init() {
		remove_action( 'parse_query', array( 'P2P_Query_Post', 'parse_query' ), 20 );
		add_action( 'parse_query', array( $this, 'parse_query' ), 20 );

		if ( class_exists( 'Tribe__Events__Query' ) ) {
			remove_action( 'parse_query', array( 'Tribe__Events__Query', 'parse_query' ), 50 );
			add_action( 'parse_query', array( 'Tribe__Events__Query', 'parse_query' ), 21 );
		}
		remove_filter( 'posts_clauses', array( 'P2P_Query_Post', 'posts_clauses' ), 20 );
		add_filter( 'posts_clauses', array( $this, 'posts_clauses' ), 20, 2 );
	}

	public function parse_query( $wp_query ) {
		$r = P2P_Query::create_from_qv( $wp_query->query_vars, 'post' );

		if ( is_wp_error( $r ) ) {
			$wp_query->_p2p_error = $r;

			$wp_query->set( 'year', 2525 );
			return;
		}

		if ( null === $r )
			return;

		list( $wp_query->_p2p_query, $wp_query->query_vars ) = $r;

		$wp_query->is_home = false;
		$wp_query->is_archive = true;
	}

	/**
	 * @param array $clauses
	 * @param \WP_Query $query
	 *
	 * @return array
	 */
	public function posts_clauses( $clauses, $query ) {
		global $wpdb;

		if ( !isset( $query->_p2p_query ) ) {
			return $clauses;
		}

		return $query->_p2p_query->alter_clauses( $clauses, "$wpdb->posts.ID" );
	}
}