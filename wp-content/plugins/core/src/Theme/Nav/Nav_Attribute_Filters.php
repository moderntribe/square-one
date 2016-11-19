<?php

namespace Tribe\Project\Theme\Nav;

/**
 * Class Nav_Attribute_Filters
 *
 * Filters the attributes applied to HTML elements in a nav menu
 */
class Nav_Attribute_Filters {

	public function hook() {
		add_filter( 'nav_menu_item_id', [ $this, 'customize_nav_item_id' ], 10, 4 );
		add_filter( 'nav_menu_css_class', [ $this, 'customize_nav_item_classes' ], 10, 4 );
		add_filter( 'nav_menu_link_attributes', [ $this, 'customize_nav_item_anchor_atts' ], 10, 4 );
		add_filter( 'walker_nav_menu_start_el', [ $this, 'customize_nav_item_start_el' ], 10, 4 );
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
	public function customize_nav_item_id( $menu_id, $item, $args, $depth ) {
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

		/*
		 *  WP Core docs claim that $args is an array, but it comes
		 * in as an object thanks to casting in wp_nav_menu()
		 */
		$args = (array) $args;

		$classes[] = $args[ 'theme_location' ] . '__list-item';

		// Depth
		$classes[] .= $args[ 'theme_location' ] . '__list-item--depth-' . $depth;

		// Has children items
		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$classes[] = $args[ 'theme_location' ] . '__list-item--has-children';
		}

		// Is Parent Item
		if ( in_array( 'current-menu-parent', $item->classes ) ) {
			$classes[] = $args[ 'theme_location' ] . '__list-item--is-parent';
		}

		// Is Current Item
		if ( in_array( 'current-menu-item', $item->classes ) ) {
			$classes[] = $args[ 'theme_location' ] . '__list-item--is-current';
		}

		$allowed_class_names = [
			$args[ 'theme_location' ] . '__list-item',
			$args[ 'theme_location' ] . '__list-item--depth-' . $depth,
			$args[ 'theme_location' ] . '__list-item--has-children',
			$args[ 'theme_location' ] . '__list-item--is-parent',
			$args[ 'theme_location' ] . '__list-item--is-current',
		];

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
	public function customize_nav_item_anchor_atts( $atts, $item, $args, $depth ) {

		/*
		 *  WP Core docs claim that $args is an array, but it comes
		 * in as an object thanks to casting in wp_nav_menu()
		 */
		$args = (array) $args;

		$classes = [
			$args[ 'theme_location' ] . '__action',
			$args[ 'theme_location' ] . '__action--depth-' . $depth,
		];

		// Has children items
		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$classes[] = $args[ 'theme_location' ] . '__action--has-children';
		}

		$atts['class'] = implode( ' ', array_unique( $classes ) );

		return $atts;

	}

	/**
	 * Customize a menu item's starting output
	 *
	 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
	 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
	 * no filter for modifying the opening and closing `<li>` for a menu item.
	 *
	 * @since 3.0.0
	 *
	 * @param string $item_output The menu item's starting HTML output.
	 * @param object $item        Menu item data object.
	 * @param int    $depth       Depth of menu item. Used for padding.
	 * @param array  $args        An array of wp_nav_menu() arguments.
	 * @return string
	 */
	public function customize_nav_item_start_el( $item_output, $item, $depth, $args ) {

		/*
		 *  WP Core docs claim that $args is an array, but it comes
		 * in as an object thanks to casting in wp_nav_menu()
		 */
		$args = (array) $args;

		// Only apply to our primary navigation
		if ( $args[ 'theme_location' ] !== 'nav-primary' ) {
			return $item_output;
		}

		// Item has children
		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$classes = [
				$args[ 'theme_location' ] . '__action',
				$args[ 'theme_location' ] . '__action--depth-' . $depth,
				$classes[] = $args[ 'theme_location' ] . '__action--has-children',
			];

			// If top level, turn <a> into <button>
			// Add general trigger markup & accessibility attributes
			$item_output = sprintf(
				'<%1$s%2$s id="menu-item-%3$s" class="%4$s" data-js="trigger-child-menu" title="%6$s">%5$s %7$s</i></%1$s>',
				0 === $depth ? 'button' : 'a',
				0 === $depth ? '' : ' href="'. esc_url( $item->url ) .'"',
				$item->ID,
				implode( ' ', array_unique( $classes ) ),
				$item->title,
				__( 'Toggle Sub-Menu', 'tribe' ),
                '<i class="'. $args[ 'theme_location' ] .'__icon-child-nav" aria-hidden="true">'
			);
		}

		return $item_output;

	}
}
