<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Footer;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Components\Footer\Navigation as Navigation_Context;
use Tribe\Project\Theme\Nav\Menu;

class Navigation extends Abstract_Controller {

	public function render( string $path = '' ): string {
		return $this->factory->get( Navigation_Context::class, [
			Navigation_Context::MENU => $this->get_primary_nav_menu(),
		] )->render( $path );
	}

	public function get_primary_nav_menu() {
		$args = [
			'theme_location'  => 'secondary',
			'container'       => false,
			'container_class' => '',
			'menu_class'      => '',
			'menu_id'         => '',
			'depth'           => 1,
			'items_wrap'      => '%3$s',
			'fallback_cb'     => false,
			'item_spacing'    => 'discard',
		];

		return Menu::menu( $args );
	}

}
