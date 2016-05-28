<?php

/**
 * Clean up WP menus while
 * removing dependency on walker
 */

class Menus {

	public function __construct() {

		add_filter( 'nav_menu_item_id', [ $this, 'clean_nav_item_id' ], 10, 4 );
		add_filter( 'nav_menu_css_class', [ $this, 'clean_nav_item_classes' ], 10, 4 );
		add_filter( 'nav_menu_link_attributes', [ $this, 'customize_menu_item_atts' ], 10, 4 );

	}

	public function clean_nav_item_id( $menu_id, $item, $args, $depth ) {

		return '';

	}

	public function clean_nav_item_classes( $classes, $item, $args, $depth ) {

		$classes[] = 'menu-item-depth-' . $depth;

		$allowed_class_names = array(
			'menu-item-has-children',
	        'current-menu-parent',
	        'current-menu-item',
	        'menu-item-depth-' . $depth,
	    );

	    return array_intersect( $allowed_class_names, $classes );

	}

	/**
	 * Customize WP menu item anchor attributes
	 */

	public function customize_menu_item_atts( $atts, $item, $args, $depth ) {

		// Primary / Site Navigation
		if ( $args->theme_location === 'primary' ) {

			// Top Level Items
			if ( 0 === $depth ) {

				$atts['class'] = 'menu-item-top-level-action';

				// Item has children
				if ( in_array( 'menu-item-has-children', $item->classes ) ) {
					$atts['class']      .= ' menu-item-parent-trigger';
					$atts['id']          = 'menu-item-' . $item->ID;
					$atts['data-target'] = 'sub-menu-' . $item->ID;
				}

			}

		}

		return $atts;

	}

}

new Menus();
