<?php

namespace Tribe\Project\Models;

use Tribe\Project\Nav_Menus\Menu as Menu_Helper;
use Tribe\Project\Nav_Menus\Walker\Walker_Nav_Menu_Primary;

class Menu extends Model {

	public function primary() {
		$args = [
			'theme_location'  => 'primary',
			'container'       => false,
			'container_class' => '',
			'menu_class'      => '',
			'menu_id'         => '',
			'depth'           => 3,
			'items_wrap'      => '%3$s',
			'fallback_cb'     => false,
			'walker'          => new Walker_Nav_Menu_Primary(),
		];

		return Menu_Helper::menu( $args );
	}

}
