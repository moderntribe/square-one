<?php
declare( strict_types=1 );

use Tribe\Libs\Nav\Menu_Location;

return [
	'menu.locations' => [
		DI\get( 'menu.primary' ),
		DI\get( 'menu.secondary' ),
	],
	'menu.primary'   => function () {
		return new Menu_Location( 'primary', __( 'Menu: Site', 'tribe' ) );
	},
	'menu.secondary' => function () {
		return new Menu_Location( 'secondary', __( 'Menu: Footer', 'tribe' ) );
	},
];

