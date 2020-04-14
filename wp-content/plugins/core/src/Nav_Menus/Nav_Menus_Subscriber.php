<?php
declare( strict_types=1 );

namespace Tribe\Project\Nav_Menus;

use Tribe\Libs\Container\Abstract_Subscriber;

class Nav_Menus_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_action( 'after_setup_theme', function () {
			foreach ( $this->container->get( Nav_Menus_Definer::LOCATIONS ) as $location ) {
				$location->register();
			}
		} );

		$this->nav_attributes();
	}

	private function nav_attributes(): void {
		add_filter( 'nav_menu_item_id', function ( $menu_id, $item, $args, $depth ) {
			return $this->container->get( Nav_Attribute_Filters::class )->customize_nav_item_id( $menu_id, $item, $args, $depth );
		}, 10, 4 );

		add_filter( 'nav_menu_css_class', function ( $classes, $item, $args, $depth ) {
			return $this->container->get( Nav_Attribute_Filters::class )->customize_nav_item_classes( $classes, $item, $args, $depth );
		}, 10, 4 );

		add_filter( 'nav_menu_link_attributes', function ( $atts, $item, $args, $depth ) {
			return $this->container->get( Nav_Attribute_Filters::class )->customize_nav_item_anchor_atts( $atts, $item, $args, $depth );
		}, 10, 4 );
	}
}
