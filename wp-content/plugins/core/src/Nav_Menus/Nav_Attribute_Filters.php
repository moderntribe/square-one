<?php

namespace Tribe\Project\Nav_Menus;

/**
 * Class Nav_Attribute_Filters
 *
 * Filters the attributes applied to HTML elements in a nav menu
 */
class Nav_Attribute_Filters {

	/**
	 * Remove the ID attributed from the nav item
	 *
	 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
	 * @param object $item    The current menu item.
	 * @param array  $args    An array of wp_nav_menu() arguments.
	 * @param int    $depth   Depth of menu item. Used for padding.
	 *
	 * @return string
	 * @filter nav_menu_item_id
	 */
	public function customize_nav_item_id( $menu_id, $item, $args, $depth ): string {
		return '';
	}

	/**
	 * Customize the CSS classes applied to an <li> in the nav menu.
	 *
	 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
	 * @param object $item    The current menu item.
	 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
	 * @param int    $depth   Depth of menu item. Used for padding.
	 *
	 * @return array
	 * @filter nav_menu_css_class
	 */
	public function customize_nav_item_classes( $classes, $item, $args, $depth ): array {

		/*
		 *  WP Core docs claim that $args is an array, but it comes
		 * in as an object thanks to casting in wp_nav_menu()
		 */
		$args = (array) $args;

		$classes[] = $args['theme_location'] . '__list-item';

		// Depth
		$classes[] = $args['theme_location'] . '__list-item--depth-' . $depth;

		// Has children items
		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$classes[] = $args['theme_location'] . '__list-item--has-children';
		}

		// Is Parent Item
		if ( in_array( 'current-menu-parent', $item->classes ) ) {
			$classes[] = $args['theme_location'] . '__list-item--is-current-parent';
		}

		// Is Current Item
		if ( in_array( 'current-menu-item', $item->classes ) ) {
			$classes[] = $args['theme_location'] . '__list-item--is-current';
		}

		/**
		 * Filter the array of classes to remove the classes added by WP Core.
		 * Regex is designed to filter all classes defined in `_wp_menu_item_classes_by_context()`;
		 */
		$classes = array_filter( $classes, function ( $class ) {

			/**
			 * Patterns used for matching:
			 *
			 * ^menu-item[\w|-]*$         Matches classes that start with `menu-item` and may or may not have additional modifiers.
			 * ^current[-|_][\w|-]*$      Matches classes that start with `current-` or `current_` and have additional modifiers.
			 * ^page[-|_]item[\w|-]*$     Matches classes that start with `page-item` or `page_item` and may or may not have additional modifiers.
			 */

			$pattern = '/^menu-item[\w|-]*$|^current[-|_][\w|-]*$|^page[-|_]item[\w|-]*$/iU';

			return ! preg_match( $pattern, $class );
		} );

		return $classes;

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
	 *
	 * @return array
	 * @filter nav_menu_link_attributes
	 */
	public function customize_nav_item_anchor_atts( $atts, $item, $args, $depth ): array {

		/*
		 *  WP Core docs claim that $args is an array, but it comes
		 * in as an object thanks to casting in wp_nav_menu()
		 */
		$args = (array) $args;

		$classes = [
			$args['theme_location'] . '__action',
			$args['theme_location'] . '__action--depth-' . $depth,
		];

		// Has children items
		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$classes[] = $args['theme_location'] . '__action--has-children';
		}

		$atts['class'] = implode( ' ', array_unique( $classes ) );

		return $atts;

	}
}
