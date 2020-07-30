<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\footer\navigation;

use Tribe\Project\Nav_Menus\Menu;
use Tribe\Project\Nav_Menus\Nav_Menus_Definer;
use Tribe\Project\Templates\Factory_Method;

class Controller {
	use Factory_Method;

	private $location = Nav_Menus_Definer::SECONDARY;

	public function label(): string {
		return __( 'Secondary Navigation', 'tribe' );
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
			'depth'           => 1,
			'items_wrap'      => '%3$s',
			'fallback_cb'     => false,
			'item_spacing'    => 'discard',
		];

		return Menu::menu( $args );
	}
}
