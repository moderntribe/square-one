<?php


namespace Tribe\Project\P2P;

class P2P_Query extends \P2P_Query {

	/** @var \P2P_Directed_Connection_Type[] */
	protected $ctypes;
	protected $items;
	protected $query;
	protected $meta;
	protected $orderby;
	protected $order;
	protected $order_num;


	/**
	 * Create instance from mixed query vars; also returns the modified query vars.
	 *
	 * @param array $q Query vars to collect parameters from
	 * @param string $object_type
	 * @return mixed
	 * - null means ignore current query
	 * - WP_Error instance if the query is invalid
	 * - array( P2P_Query, array )
	 */
	public static function create_from_qv( $q, $object_type ) {
		$parent = \P2P_Query::create_from_qv( $q, $object_type );
		if ( is_wp_error( $parent ) ) {
			return $parent;
		}
		if ( empty( $parent ) ) {
			return $parent;
		}

		list( $p2p_q, $q ) = $parent;

		$local_p2p_q = new \P2P_Query();

		$local_p2p_q->ctypes = $p2p_q->ctypes;
		$local_p2p_q->items = $p2p_q->items;

		foreach ( [ 'meta', 'orderby', 'order_num', 'order' ] as $key ) {
			$local_p2p_q->$key = $p2p_q->$key;
		}

		$local_p2p_q->query = $p2p_q->query;

		return [ $local_p2p_q, $q ];
	}


	/**
	 * For low-level query modifications
	 */
	public function alter_clauses( &$clauses, $main_id_column ) {
		if ( !$this->should_override_parent() ) {
			return \P2P_Query::alter_clauses( $clauses, $main_id_column );
		}

		/** @var \wpdb $wpdb */
		global $wpdb;

		$joins = '';
		$join_count = 0;

		$where_parts = [];

		$connected_post_ids = _p2p_normalize( $this->items );
		$connected_post_ids = array_map( 'intval', $connected_post_ids );
		$connected_post_ids = implode(',', $connected_post_ids);

		foreach ( $this->ctypes as $directed ) {
			$to_alias = 'p2p'.$join_count++;
			$to_side = $directed->get( 'opposite', 'side' ); // opposite == to for the 'any' direction
			$to_join = $wpdb->prepare(
				" LEFT JOIN {$wpdb->p2p} $to_alias
				 ON $main_id_column=$to_alias.p2p_to
				  AND $to_alias.p2p_type= %s",
				$directed->name
			);
			if ( !empty( $connected_post_ids ) ) {
				$to_join .= " AND $to_alias.p2p_from IN ( $connected_post_ids )";
			}
			if ( !empty( $to_side->query_vars['post_type'] ) ) {
				$post_types = $to_side->query_vars['post_type'];
				array_walk( $post_types, [ $wpdb, 'escape_by_ref' ] );
				$post_type_string = implode( "','", $post_types );
				$to_join .= " AND {$wpdb->posts}.post_type IN ('$post_type_string')";
			}
			$joins .= $to_join;

			$from_alias = 'p2p'.$join_count++;
			$from_side = $directed->get( 'current', 'side' ); // current == from for the 'any' direction
			$from_join = $wpdb->prepare(
				" LEFT JOIN {$wpdb->p2p} $from_alias
				 ON $main_id_column=$from_alias.p2p_from
				  AND $from_alias.p2p_type= %s",
				$directed->name
			);
			if ( !empty( $connected_post_ids ) ) {
				$from_join .= " AND $from_alias.p2p_to IN ( $connected_post_ids )";
			}
			if ( !empty( $from_side->query_vars['post_type'] ) ) {
				$post_types = $from_side->query_vars['post_type'];
				array_walk( $post_types, [ $wpdb, 'escape_by_ref' ] );
				$post_type_string = implode( "','", $post_types );
				$from_join .= " AND {$wpdb->posts}.post_type IN ('$post_type_string')";
			}
			$joins .= $from_join;

			$where_parts[] = "( $to_alias.p2p_to IS NOT NULL OR $from_alias.p2p_from IS NOT NULL )";
		}

		$clauses['join'] .= $joins;

		$clauses['groupby'] = "$main_id_column";

		if ( 1 == count( $where_parts ) ) {
			$clauses['where'] .= " AND " . $where_parts[0];
		} elseif ( !empty( $where_parts ) ) {
			$clauses['where'] .= " AND (" . implode( ' OR ', $where_parts ) . ")";
		}

		// Handle custom fields
		if ( !empty( $this->meta ) ) {
			$meta_clauses = _p2p_meta_sql_helper( $this->meta );
			foreach ( $meta_clauses as $key => $value ) {
				$clauses[ $key ] .= $value;
			}
		}

		// Handle ordering
		if ( $this->orderby ) {
			$clauses['join'] .= $wpdb->prepare( "
				LEFT JOIN $wpdb->p2pmeta AS p2pm_order ON (
					$wpdb->p2p.p2p_id = p2pm_order.p2p_id AND p2pm_order.meta_key = %s
				)
			", $this->orderby );

			$order = ( 'DESC' == strtoupper( $this->order ) ) ? 'DESC' : 'ASC';

			$field = 'meta_value';

			if ( $this->order_num ) {
				$field .= '+0';
			}

			$clauses['orderby'] = "p2pm_order.$field $order, " . str_replace( 'ORDER BY ', '', $clauses['orderby'] );
		}

		return $clauses;
	}

	/**
	 * Only handle the case of bi-directional searches with posts on both sides
	 *
	 * @return bool
	 */
	private function should_override_parent() {
		foreach ( $this->ctypes as $directed ) {
			if ( null === $directed ) { // used by migration script
				false;
			}
			switch ( $directed->get_direction() ) {
				case 'from':
					return false;
				case 'to':
					return false;
				default:
					$to_side = $directed->get( 'current', 'side' );
					if ( ! $to_side instanceof \P2P_Side_Post ) {
						return false;
					}
					$from_side = $directed->get( 'opposite', 'side' );
					if ( ! $from_side instanceof \P2P_Side_Post ) {
						return false;
					}
					break;
			}
		}
		return true;
	}
}
