<?php


namespace Tribe\Project\Theme\Nav;

/**
 * Class Nav_Attribute_Filters
 *
 * Filters the attributes applied to HTML elements in a nav menu
 */
class Nav_Attribute_Filters {

	public function hook() {

		add_filter( 'nav_menu_item_id', [ $this, 'clean_nav_item_id' ], 10, 4 );
		add_filter( 'nav_menu_css_class', [ $this, 'customize_nav_item_classes' ], 10, 4 );
		add_filter( 'nav_menu_link_attributes', [ $this, 'customize_menu_item_atts' ], 10, 4 );

	}


	/**
	 * Remove the ID attributed from the nav item
	 *
	 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
	 * @param object $item    The current menu item.
	 * @param array  $args    An array of wp_nav_menu() arguments.
	 * @param int    $depth   Depth of menu item. Used for padding.
	 * @return string
	 */
	public function clean_nav_item_id( $menu_id, $item, $args, $depth ) {

		return '';

	}

	/**
	 * Customize the CSS classes applied to an <li> in the nav menu.
	 *
	 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
	 * @param object $item    The current menu item.
	 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
	 * @param int    $depth   Depth of menu item. Used for padding.
	 * @return array
	 */
	public function customize_nav_item_classes( $classes, $item, $args, $depth ) {

		$classes[] = 'menu-item--depth-' . $depth;

		$allowed_class_names = array(
			'menu-item',
			'menu-item-has-children',
			'current-menu-parent',
			'current-menu-item',
			'menu-item--depth-' . $depth,
		);

		return array_intersect( $allowed_class_names, $classes );

	}

	/**
	 * Customize WP menu item anchor attributes
	 *
	 * @param array $atts {
	 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
	 *
	 *     @type string $title  Title attribute.
	 *     @type string $target Target attribute.
	 *     @type string $rel    The rel attribute.
	 *     @type string $href   The href attribute.
	 * }
	 * @param object $item  The current menu item.
	 * @param array  $args  An array of {@see wp_nav_menu()} arguments.
	 * @param int    $depth Depth of menu item. Used for padding.
	 * @return array
	 */
	public function customize_menu_item_atts( $atts, $item, $args, $depth ) {

		/*
		 *  WP Core docs claim that $args is an array, but it comes
		 * in as an object thanks to casting in wp_nav_menu()
		 */
		$args = (array) $args;
		// Primary / Site Navigation
		if ( $args[ 'theme_location' ] === 'primary' ) {
			// Top Level Items
			if ( 0 === $depth ) {
				$atts['class'] = 'menu-item__anchor';

				// Item has children
				if ( in_array( 'menu-item-has-children', $item->classes ) ) {
					$atts['class']      .= ' menu-item__anchor--child';
					$atts['id']          = 'menu-item-' . $item->ID;
					$atts['data-target'] = 'sub-menu-' . $item->ID;
				}
			}
		}

		return $atts;

	}
}
