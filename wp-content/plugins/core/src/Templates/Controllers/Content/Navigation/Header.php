<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Content\Navigation;

use Tribe\Project\Service_Providers\Nav_Menu_Provider;
use Tribe\Project\Templates\Abstract_Template;

class Header extends Abstract_Template {
	protected $path = 'content/navigation/header.twig';

	public function get_data(): array {
		return [
			'menu' => [
				'primary' => $this->get_primary_nav_menu(),
			],
		];
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
			'walker'          => new \Tribe\Project\Theme\Nav\Walker_Nav_Menu_Primary(),
		];

		return \Tribe\Project\Theme\Nav\Menu::menu( $args );
	}

}
