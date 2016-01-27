<?php


namespace Tribe\Project\P2P;


class Event_Query_Filters {
	public function hook() {
		add_action( 'tribe_events_pre_get_posts', array( $this, 'remove_event_filters_from_p2p_query' ) );
		add_action( 'wp_ajax_posts-field-p2p-options-search', array( $this, 'remove_event_filters_from_panel_p2p_requests' ), 0, 0);
	}

	/**
	 * @param \WP_Query $query
	 *
	 * @return void
	 */
	public function remove_event_filters_from_p2p_query( $query ) {
		if ( !empty( $query->tribe_is_multi_posttype ) && $query->get( 'connected_items' ) ) {
			$query->tribe_is_multi_posttype = false;
		}
	}

	public function remove_event_filters_from_panel_p2p_requests() {
		remove_action( 'parse_query', array( 'Tribe__Events__Query', 'parse_query' ), 50 );
		remove_action( 'pre_get_posts', array( 'Tribe__Events__Query', 'pre_get_posts' ), 50 );
	}
}