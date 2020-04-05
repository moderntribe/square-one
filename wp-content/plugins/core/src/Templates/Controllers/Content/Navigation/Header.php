<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Content\Navigation;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Components\Header\Navigation;
use Tribe\Project\Theme\Nav\Menu;
use Tribe\Project\Theme\Nav\Walker_Nav_Menu_Primary;

class Header extends Abstract_Template {
	public function render( string $path = '' ): string {
		return $this->factory->get( Navigation::class, [
			Navigation::MENU => $this->get_primary_nav_menu(),
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
