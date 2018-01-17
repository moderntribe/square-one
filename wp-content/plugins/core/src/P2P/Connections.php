<?php

namespace Tribe\Project\P2P;

/**
 * Class Connections
 * @package Tribe\Project\P2P
 *
 * Connections is a class of helper functions for extracting p2p data while bypassing some of the more complex
 * methods need using the native p2p functionality.
 *
 * These methods should be used for simple needs.  If building complicated p2p integration make sure there's
 * no standard methodoligy.  If simply needing some connections and post ids this will allow you to bypass
 * some of the more complex query building and filtering that p2p core does when using functionality like
 *
 * new WP_Query( [ 'connected_type' => ####, 'connected_items' => get_queried_object() ] );
 *
 * instead you could use
 *
 * $connections = tribe_project()->container['p2p.connections'];
 * $ids = $connections->get_from( ##, [ 'type' => 'posts_to_pages' ] );
 *
 * Which will only perform a single DB query and return an array of ids.
 */
class Connections {

	/**
	 * Get all ids of posts connected TO ID of single post
	 *
	 * @param int|string $to_id
	 * @param array $args
	 *
	 * $args['type'] => Specify a p2p connection type
	 * $args['order'] => Optionally return results ordered by p2p_id using 'ASC' or 'DESC'
	 *
	 * @return array
	 */
	public function get_from( $to_id, $args = [] ) : array {
		return $this->get_ids( 'p2p_to', 'p2p_from', $to_id, $args );
	}

	/**
	 * Get all ids of posts connected FROM ID of single post
	 *
	 * @param int|string $from_id
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_to( $from_id, $args = [] ) : array {
		return $this->get_ids( 'p2p_from', 'p2p_to', $from_id, $args );
	}

	/**
	 * Grab the newest connection made for a p2p_type
	 *
	 * @param $p2p_type
	 *
	 * @return array
	 */
	public function get_newest_connection( $p2p_type ) : array {
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->p2p} WHERE p2p_type=%s ORDER BY p2p_id DESC LIMIT 1", $p2p_type );

		return $wpdb->get_row( $sql, ARRAY_A );
	}

	//////////////////////////////////////
	///////// PRIVATE FUNCTIONS //////////
	//////////////////////////////////////

	/**
	 * @param $select
	 * @param $where
	 * @param $id
	 * @param array $args
	 *
	 * @return array
	 */
	private function get_ids( $select, $where, $id, $args = [] ) : array {
		global $wpdb;
		$select = esc_sql( $select );
		$where = esc_sql( $where );
		$sql = $wpdb->prepare( "SELECT $select FROM {$wpdb->p2p} WHERE $where=%d", $id );

		if ( isset( $args['type'] ) ) {
			$sql .= $wpdb->prepare( " AND p2p_type=%s", $args['type'] );
		}

		if ( isset( $args['order'] ) ) {
			$order = esc_sql( $args['order'] );
			$sql .= " ORDER BY p2p_id $order";
		}

		return $wpdb->get_col( $sql );
	}

}