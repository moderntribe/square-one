<?php declare(strict_types=1);

namespace Tribe\Project\Nav_Menus;

use stdClass;

/**
 * Class Nav_Attribute_Filters
 *
 * Filters the attributes applied to HTML elements in a nav menu
 */
class Nav_Attribute_Filters {

	/**
	 * Generate a class prefix based on wp_nav_menu() parameters.
	 *
	 * Because `menu` overrides `theme_location`, and `theme_location` is used if
	 * `menu` is empty or menu does not exist, the solution is to get the slugs
	 * of both menus, and if they're equal, use the theme location. If they're not equal,
	 * then use the menu slug.
	 *
	 * @param stdClass $args An object of {@see wp_nav_menu()} arguments.
	 *
	 * @return string
	 *
	 * @todo evaluate: prepending "location-" and "menu-" is backwards-compatibility issue
	 */
	protected function get_class_prefix( stdClass $args ): string {
		$menu = wp_get_nav_menu_object( $args->menu );
		$locations = get_nav_menu_locations();

		$menu_slug = '';
		$location_menu_slug = '';

		// Get slug of menu if exists.
		if ( $menu ) {
			$menu_slug = $menu->slug;
		}

		// Get theme location's menu.
		if ( $args->theme_location && $locations && isset( $locations[ $args->theme_location ] ) ) {
			$location_menu = wp_get_nav_menu_object( $locations[ $args->theme_location ] );
		}

		// Get theme location's menu's slug.
		if ( ! empty( $location_menu ) ) {
			$location_menu_slug = $location_menu->slug;
		}

		// Both slugs are empty, we've got nothing: bail.
		if ( ! $menu_slug && ! $location_menu_slug ) {
			return '';
		}

		// Menu and location menu slug are equal: use theme location.
		if ( $menu_slug === $location_menu_slug ) {
			return 'location-' . $args->theme_location;
		}

		// Menu slug is empty and theme location is specified: use theme location.
		if ( ! $menu_slug && $args->theme_location ) {
			return 'location-' . $args->theme_location;
		}

		// Must be a specified menu.
		return 'menu-' . $menu_slug;
	}

	/**
	 * Remove the ID attributed from the nav item
	 *
	 * @param string    $menu_id The ID that is applied to the menu item's `<li>` element.
	 * @param object    $item    The current menu item.
	 * @param \stdClass $args    An object of {@see wp_nav_menu()} arguments.
	 * @param int       $depth   Depth of menu item. Used for padding.
	 *
	 * @return string
	 *
	 * @filter nav_menu_item_id
	 */
	public function customize_nav_item_id( string $menu_id, object $item, stdClass $args, int $depth ): string {
		return '';
	}

	/**
	 * Customize the CSS classes applied to an <li> in the nav menu.
	 *
	 * @param array     $classes The CSS classes that are applied to the menu item's `<li>` element.
	 * @param object    $item    The current menu item.
	 * @param \stdClass $args    An object of {@see wp_nav_menu()} arguments.
	 * @param int       $depth   Depth of menu item. Used for padding.
	 *
	 * @note   WP Core docs claim that $args is an array, but it comes in as an object thanks to casting in wp_nav_menu().
	 *
	 * @return array
	 *
	 * @filter nav_menu_css_class
	 */
	public function customize_nav_item_classes( array $classes, object $item, stdClass $args, int $depth ): array {
		$class_prefix = $this->get_class_prefix( $args );

		if ( empty( $class_prefix ) ) {
			return $classes;
		}

		$classes[] = $class_prefix . '__list-item';

		// Depth
		$classes[] = $class_prefix . '__list-item--depth-' . $depth;

		// Has children items
		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$classes[] = $class_prefix . '__list-item--has-children';
		}

		// Is Parent Item
		if ( in_array( 'current-menu-parent', $item->classes ) ) {
			$classes[] = $class_prefix . '__list-item--is-current-parent';
		}

		// Is Current Item
		if ( in_array( 'current-menu-item', $item->classes ) ) {
			$classes[] = $class_prefix . '__list-item--is-current';
		}

		/**
		 * Filter the array of classes to remove the classes added by WP Core.
		 * Regex is designed to filter all classes defined in `_wp_menu_item_classes_by_context()`;
		 */
		return array_filter( $classes, static function ( $class ) {

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
	}

	/**
	 * Customize WP menu item anchor attributes
	 *
	 * @param array     $atts   {
	 *                          The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
	 *
	 * @type string     $title  Title attribute.
	 * @type string     $target Target attribute.
	 * @type string     $rel    The rel attribute.
	 * @type string     $href   The href attribute.
	 * }
	 *
	 * @param object    $item   The current menu item.
	 * @param \stdClass $args   An object of {@see wp_nav_menu()} arguments.
	 * @param int       $depth  Depth of menu item. Used for padding.
	 *
	 * @return array
	 *
	 * @filter nav_menu_link_attributes
	 */
	public function customize_nav_item_anchor_atts( array $atts, object $item, stdClass $args, int $depth ): array {
		$class_prefix = $this->get_class_prefix( $args );

		if ( empty( $class_prefix ) ) {
			return $atts;
		}

		$classes = [
			$class_prefix . '__action',
			$class_prefix . '__action--depth-' . $depth,
		];

		// Has children items
		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$classes[] = $class_prefix . '__action--has-children';
		}

		$atts['class'] = implode( ' ', array_unique( $classes ) );

		return $atts;
	}

}
