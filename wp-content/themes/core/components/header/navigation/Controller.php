<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\header\navigation;

use Tribe\Project\Nav_Menus\Menu;
use Tribe\Project\Nav_Menus\Nav_Menus_Definer;
use Tribe\Project\Nav_Menus\Walker\Walker_Nav_Menu_Primary;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {

	private $location = Nav_Menus_Definer::PRIMARY;

	public function label(): string {
		return __( 'Primary Navigation', 'tribe' );
	}

	public function has_menu(): bool {
		return has_nav_menu( $this->location );
	}

	public function menu(): string {
		$args = [
			'theme_location'  => $this->location,
			'container'       => false,
			'container_class' => '',
			'menu_class'      => '',
			'menu_id'         => '',
			'depth'           => 3,
			'items_wrap'      => '%3$s',
			'fallback_cb'     => false,
			'walker'          => new Walker_Nav_Menu_Primary(),
		];

		return Menu::menu( $args );
	}
}
