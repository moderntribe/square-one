<?php
declare( strict_types=1 );

namespace Tribe\Project\Nav_Menus;

use Tribe\Libs\Container\Abstract_Subscriber;

class Nav_Menus_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_action( 'after_setup_theme', function () {
			foreach ( $this->container->get( Nav_Menus_Definer::LOCATIONS ) as $location ) {
				$location->register();
			}
		} );
	}
}
