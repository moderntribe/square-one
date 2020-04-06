<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Header;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Components\Header\Navigation as Navigation_Context;
use Tribe\Project\Theme\Nav\Menu;
use Tribe\Project\Theme\Nav\Walker_Nav_Menu_Primary;

class Navigation extends Abstract_Controller {
	public function render( string $path = '' ): string {
		return $this->factory->get( Navigation_Context::class, [
			Navigation_Context::MENU => $this->get_primary_nav_menu(),
		] )->render( $path );
	}

	public function get_primary_nav_menu() {
		$args = [
			'theme_location'  => 'primary',
			'container'       => false,
			'container_class' => '',
			'menu_class'      => '',
			'menu_id'         => '',
			'depth'           => 3,
			'items_wrap'      => '%3$s',
			'fallback_cb'     => false,
			'echo'            => false,
			'walker'          => new Walker_Nav_Menu_Primary(),
		];

		return Menu::menu( $args );
	}

}
