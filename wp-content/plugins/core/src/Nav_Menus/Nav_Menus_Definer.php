<?php
declare( strict_types=1 );

namespace Tribe\Project\Nav_Menus;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Libs\Nav\Menu_Location;

class Nav_Menus_Definer implements Definer_Interface {
	public const LOCATIONS = 'menu.locations';
	public const PRIMARY   = 'primary';
	public const SECONDARY = 'secondary';

	public function define(): array {
		return [
			self::LOCATIONS  => [
				DI\get( 'menu.primary' ),
				DI\get( 'menu.secondary' ),
			],
			'menu.primary'   => function () {
				return new Menu_Location( Nav_Menus_Definer::PRIMARY, __( 'Menu: Site', 'tribe' ) );
			},
			'menu.secondary' => function () {
				return new Menu_Location( Nav_Menus_Definer::SECONDARY, __( 'Menu: Footer', 'tribe' ) );
			},
		];
	}

}
