<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Content\Navigation;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Components\Footer\Navigation;
use Tribe\Project\Theme\Nav\Menu;

class Footer extends Abstract_Template {
	protected $path = 'content/navigation/footer.twig';

	public function render( string $path = '' ): string {
		return $this->factory->get( Navigation::class, [
			Navigation::MENU => $this->get_primary_nav_menu(),
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
			'echo'            => false,
			'item_spacing'    => 'discard',
		];

		return Menu::menu( $args );
	}

}
