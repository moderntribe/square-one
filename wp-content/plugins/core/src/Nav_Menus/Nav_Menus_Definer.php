<?php declare(strict_types=1);

namespace Tribe\Project\Nav_Menus;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Libs\Nav\Menu_Location;

class Nav_Menus_Definer implements Definer_Interface {

	public const LOCATIONS = 'menu.locations';
	public const PRIMARY   = 'primary';
	public const FOOTER    = 'footer';
	public const LEGAL     = 'legal';

	public function define(): array {
		return [
			self::LOCATIONS => [
				DI\get( 'menu.primary' ),
				DI\get( 'menu.footer' ),
				DI\get( 'menu.legal' ),
			],
			'menu.primary'  => static function () {
				return new Menu_Location( Nav_Menus_Definer::PRIMARY, esc_html__( 'Masthead: Main', 'tribe' ) );
			},
			'menu.footer'   => static function () {
				return new Menu_Location( Nav_Menus_Definer::FOOTER, esc_html__( 'Footer: Primary', 'tribe' ) );
			},
			'menu.legal'    => static function () {
				return new Menu_Location( Nav_Menus_Definer::LEGAL, esc_html__( 'Footer: Legal', 'tribe' ) );
			},
		];
	}

}
