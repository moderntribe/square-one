<?php

namespace Tribe\Project\P2P;

/**
 * Class Connections
 * @package Tribe\Project\P2P
 *
 * Connections is a class of helper functions for extracting p2p data while bypassing some of the more complex
 * methods needed for using the native p2p functionality.
 *
 * These methods should be used for simple needs.  If building complicated p2p integration make sure there's
 * no standard methodology.  If simply needing some connections and post ids this will allow you to bypass
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
	 * $args['type'] => Specify a p2p connection type or array of types
	 * $args['order'] => Optionally return results ordered by p2p_id using 'ASC' or 'DESC'
	 * $args['meta'] => Optional array with key & optional value to filter results [ 'key' => '###', 'value' => '###' ]
	 *
	 * @return array
	 */
	public function get_from( $to_id, $args = [] ) {
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
	public function get_to( $from_id, $args = [] ) {
		return $this->get_ids( 'p2p_from', 'p2p_to', $from_id, $args );
	}

	/**
	 * @param string $meta_key
	 * @param string|bool $meta_value
	 * @param array $args
	 *
	 * @return array|null|object
	 */
	public function get_connections_by_p2p_meta( $meta_key, $meta_value = false, $args = [] ) {
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->p2p} LEFT JOIN {$wpdb->p2pmeta} AS p2pm ON p2pm.meta_key=%s", $meta_key );

		if ( ! empty( $meta_value ) ) {
			$sql .= $wpdb->prepare( " AND p2pm.meta_value=%s", $meta_value );
		}

		$sql .= "WHERE {$wpdb->p2p}.p2p_id = p2pm.p2p_id";

		if ( isset( $args['type'] ) ) {
			$sql .= $wpdb->prepare( " AND {$wpdb->p2p}.p2p_type=%s", $args['type'] );
		}

		return $wpdb->get_results( $sql );
	}

	/**
	 * Grab the newest connection made for a p2p_type
	 *
	 * @param $p2p_type
	 *
	 * @return array
	 */
	public function get_newest_connection( $p2p_type ) {
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->p2p} WHERE p2p_type=%s ORDER BY p2p_id DESC LIMIT 1", $p2p_type );

		return $wpdb->get_row( $sql, ARRAY_A );
	}

	//////////////////////////////////////
	///////// PRIVATE FUNCTIONS //////////
	//////////////////////////////////////

	/**
	 * @param string $direction 'p2p_to' or 'p2p_from'
	 * @param string $where 'p2p_to' or 'p2p_from'
	 * @param string $id
	 * @param array $args
	 *
	 * @return array
	 */
	private function get_ids( $direction, $where, $id, $args = [] ) {
		global $wpdb;
		$direction = esc_sql( $direction );
		$sql = "SELECT $direction FROM {$wpdb->p2p}";

		if ( isset( $args['meta']['key'] ) ) {
			$value = isset( $args['meta']['value'] ) ? $args['meta']['value'] : false;
			$sql .= ' ' . $this->prepare_meta_join( $direction, $args['meta']['key'], $value );
		}

		$where = esc_sql( $where );
		$sql .= apply_filters( 'tribe_p2p_where_sql', $wpdb->prepare( " WHERE $where=%d", $id ) );

		/** Set the connection type (relationship) */
		if ( isset( $args['type'] ) ) {
			$type = is_array( $args['type'] ) ? ' IN ("' . implode( '","', esc_sql( $args['type'] ) ) . '")' : '="' . esc_sql( $args['type'] ) . '"';
			$sql .= apply_filters( 'tribe_p2p_type_sql', ' AND p2p_type' . $type );
		}

		/** Set order by */
		if ( isset( $args['orderby'] ) && $args['orderby'] === 'ids' ) {
			$orderby = $direction;
		}

		/** Set order */
		if ( isset( $args['order'] ) ) {
			$order = esc_sql( $args['order'] );
			$orderby = isset( $orderby ) ? esc_sql( $orderby ) : 'p2p_id';
			$sql .= " ORDER BY $orderby $order";
		}

		/** Allow filtering of entire sql statement before query */
		$sql = apply_filters( 'tribe_p2p_get_ids_sql', $sql );

		return array_map( 'intval', $wpdb->get_col( $sql ) );
	}

	/**
	 * @param string $direction
	 * @param string $meta_key
	 * @param bool|string $meta_value
	 *
	 * @return string
	 */
	private function prepare_meta_join( $direction, $meta_key, $meta_value = false ) {
		global $wpdb;

		$direction = esc_sql( $direction );
		$join = $wpdb->prepare( "
			LEFT JOIN {$wpdb->postmeta} AS pm
			ON {$wpdb->p2p}.$direction = pm.post_id AND pm.meta_key=%s
		", $meta_key );

		if ( ! empty( $meta_value ) ) {
			$join .= $wpdb->prepare( " AND pm.meta_value=%s", $meta_value );
		}

		$inject_where = function( $where ) use ( $direction ) {
			global $wpdb;
			return $where . " AND pm.post_id = {$wpdb->p2p}.$direction";
		};
		add_filter( 'tribe_p2p_where_sql', $inject_where );

		return $join;
	}

}