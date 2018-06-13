<?php


namespace Tribe\Project\Nav;
use Walker;


abstract class Object_Nav_Walker extends Walker  {

	/**
	 * What the class handles.
	 *
	 * @see Walker::$tree_type
	 * @var string
	 */
	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	/**
	 * Database fields to use.
	 *
	 * @see Walker::$db_fields
	 * @var array
	 */
	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth. It is possible to set the
	 * max depth to include all depths, see walk() method.
	 *
	 * This method should not be called directly, use the walk() method instead.
	 *
	 * @since 2.5.0
	 *
	 * @param object $element           Data object.
	 * @param array  $children_elements List of elements to continue traversing.
	 * @param int    $max_depth         Max depth to traverse.
	 * @param int    $depth             Depth of current element.
	 * @param array  $args              An array of arguments.
	 * @param string $output            Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {

		if ( !$element ) {
			return;
		}

		if ( !is_array($output) ) {
			$output = array();
		}

		$id_field = $this->db_fields['id'];

		//display this element
		if ( isset( $args[0] ) && is_array( $args[0] ) )
			$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array($this, 'start_el'), $cb_args);

		$id = $element->$id_field;

		$classes = empty( $element->classes ) ? array() : (array) $element->classes;
		$classes[] = 'menu-item-' . $element->ID;
		$classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $element, $args[0], $depth );
		$output['menu_id'] = $id;
		if ( !isset( $element->post_type ) || $element->post_type !== 'nav_menu_item' ) {
			if ( $element->type === 'post_type' ) {
				$output['menu_id'] = $element->post_type . '-' . $output['menu_id'];
			} elseif( $element->type === 'taxonomy' ) {
				$output['menu_id'] = $element->taxonomy . '-' . $output['menu_id'];
			} else {
				$output['menu_id'] = $element->type . '-' . $output['menu_id'];
			}
		}
		$output['classes'] = isset( $output['classes'] ) ? $output['classes'] : array();
		$output['classes'] = implode( ' ', array_merge( $output['classes'], $classes ) );
		
		// descend only when the depth is right and there are children for this element
		if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {
			$output['has_children'] = true;
			foreach( $children_elements[ $id ] as $child ){
				$child_array = array();
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $child_array );
				$output['menu_items'][] = $child_array;
			}
			unset( $children_elements[ $id ] );
		} else {
			$output['has_children'] = false;
		}
	}



	/**
	 * Start the element output.
	 *
	 * The $args parameter holds additional values that may be used with the child
	 * class methods. Includes the element output also.
	 *
	 * @param string|array $output            Passed by reference. Used to append additional content.
	 * @param object $item            The data object.
	 * @param int    $depth             Depth of the item.
	 * @param array  $args              An array of additional arguments.
	 * @param int    $id ID of the current item.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ( !is_array($output) ) {
			$output = array();
		}
		$id_field = $this->db_fields['id'];

		remove_filter( 'the_title', 'wptexturize' );
		$output['label'] = apply_filters( 'the_title', $item->title, $item->ID );
		if( !empty( $args->numeric_ids ) ){
			$output['id'] = apply_filters( 'nav_menu_item_id', $item->ID, $item, $args, $depth );
		} else {
			$output['id'] = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		}
		$output['excerpt'] = $item->description;
		$output['summary_link_label'] = apply_filters( 'summary_link_label', $item->summary_link_label, $item->ID );
		if ( isset( $item->post_type ) && $item->post_type == 'nav_menu_item' ) { // only get classes if we're looking at a nav menu item
			$classes = get_post_meta( $item->$id_field, '_menu_item_classes', TRUE );
		}
		if ( !empty($classes) && is_array($classes) ) {
			$output['classes'] = $classes;
		}

		foreach ( array( 'date', 'time', 'datetime', 'timestamp') as $time_key ) {
			if ( !empty($item->$time_key) ) {
				$output[$time_key] = $item->$time_key;
			}
		}

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['url']   = ! empty( $item->url )        ? $item->url        : '';

		/**
		 * Filter the HTML attributes applied to a menu item's <a>.
		 *
		 * @since 3.6.0
		 *
		 * @see wp_nav_menu()
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's <a>, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param object $item The current menu item.
		 * @param array  $args An array of wp_nav_menu() arguments.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		foreach ( $atts as $key => $value ) {
			$output[$key] = $value;
		}

		$output = array_filter($output);
		$output = apply_filters( 'object_nav_walker_start_el', $output, $item, $depth, $args );
	}



	/**
	 * Display array of elements hierarchically.
	 *
	 * Does not assume any existing order of elements.
	 *
	 * $max_depth = -1 means flatly display every element.
	 * $max_depth = 0 means display all levels.
	 * $max_depth > 0 specifies the number of display levels.
	 *
	 * @param array $elements  An array of elements.
	 * @param int   $max_depth The maximum hierarchical depth.
	 * @return string The hierarchical item output.
	 */
	function walk( $elements, $max_depth) {

		$args = array_slice(func_get_args(), 2);
		$menu_items = array();

		if ($max_depth < -1) { //invalid parameter
			return $this->format_output($menu_items);
		}

		if (empty($elements)) { //nothing to walk
			return $this->format_output($menu_items);
		}

		$id_field = $this->db_fields['id'];
		$parent_field = $this->db_fields['parent'];

		// flat display
		if ( -1 == $max_depth ) {
			$empty_array = array();
			foreach ( $elements as $e ) {
				$item = array();
				$this->display_element( $e, $empty_array, 1, 0, $args, $item );
				$menu_items[] = $item;
			}
			return $this->format_output($menu_items);
		}

		/*
		 * Need to display in hierarchical order.
		 * Separate elements into two buckets: top level and children elements.
		 * Children_elements is two dimensional array, eg.
		 * Children_elements[10][] contains all sub-elements whose parent is 10.
		 */
		$top_level_elements = array();
		$children_elements  = array();
		foreach ( $elements as $e) {
			if ( 0 == $e->$parent_field )
				$top_level_elements[] = $e;
			else
				$children_elements[ $e->$parent_field ][] = $e;
		}

		/*
		 * When none of the elements is top level.
		 * Assume the first one must be root of the sub elements.
		 */
		if ( empty($top_level_elements) ) {

			$first = array_slice( $elements, 0, 1 );
			$root = $first[0];

			$top_level_elements = array();
			$children_elements  = array();
			foreach ( $elements as $e) {
				if ( $root->$parent_field == $e->$parent_field )
					$top_level_elements[] = $e;
				else
					$children_elements[ $e->$parent_field ][] = $e;
			}
		}

		foreach ( $top_level_elements as $e ) {
			$item = array();
			$this->display_element( $e, $children_elements, $max_depth, 0, $args, $item );
			$menu_items[] = $item;
		}

		/*
		 * If we are displaying all levels, and remaining children_elements is not empty,
		 * then we got orphans, which should be displayed regardless.
		 */
		if ( ( $max_depth == 0 ) && count( $children_elements ) > 0 ) {
			$empty_array = array();
			foreach ( $children_elements as $orphans ) {
				foreach( $orphans as $op ) {
					$item = array();
					$this->display_element( $op, $empty_array, 1, 0, $args, $item );
					$menu_items[] = $item;
				}
			}
		}

		return $this->format_output($menu_items);
	}

	/**
	 * This is the reason the class is abstract. walk() expects this
	 * method to return a string, but it gives an array. Subclasses
	 * must override this to format to a string before returning.
	 *
	 * @param array $menu_items
	 * @return array
	 */
	protected function format_output( $menu_items ) {
		return array(
			'menu_items' => $menu_items,
		);
	}
}
