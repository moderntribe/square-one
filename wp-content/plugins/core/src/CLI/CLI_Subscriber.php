<?php
declare( strict_types=1 );

namespace Tribe\Project\CLI;

use Tribe\Libs\Container\Abstract_Subscriber;

class CLI_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_action( 'init', function () {
			if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
				return;
			}

			$this->container->get( Cache_Prime::class )->register();
		}, 0, 0 );
	}
}
