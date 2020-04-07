<?php
declare( strict_types=1 );

namespace Tribe\Project\Nav_Menus;

use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Subscriber_Interface;

class Nav_Menus_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		add_action( 'after_setup_theme', function () use ( $container ) {
			foreach ( $container->get( Nav_Menus_Definer::LOCATIONS ) as $location ) {
				$location->register();
			}
		} );
	}
}
