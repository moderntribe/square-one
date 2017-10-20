<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Libs\Nav\Menu_Location;
use Tribe\Project\Theme\Nav\Menu\Primary_Menu;
use Tribe\Project\Theme\Nav\Menu\Secondary_Menu;

class Nav_Menu_Provider implements ServiceProviderInterface {

	protected $nav_menus = [];

	public function __construct() {
		$this->nav_menus = [
			Primary_Menu::NAME   => __( 'Menu: Site', 'tribe' ),
			Secondary_Menu::NAME => __( 'Menu: Footer', 'tribe' ),
		];
	}

	public function register( Container $container ) {
		foreach ( $this->nav_menus as $location => $description ) {
			$container[ 'menu.' . $location ] = function ( $container ) use ( $location, $description ) {
				return new Menu_Location( $location, $description );
			};
			add_action( 'plugins_loaded', function () use ( $container, $location ) {
				$container[ 'menu.' . $location ]->hook();
			}, 10, 0 );
		}
	}
}