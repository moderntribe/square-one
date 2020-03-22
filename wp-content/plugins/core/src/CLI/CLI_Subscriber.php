<?php
declare( strict_types=1 );

namespace Tribe\Project\CLI;

use Psr\Container\ContainerInterface;
use Tribe\Project\Container\Subscriber_Interface;

class CLI_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		add_action( 'init', function () use ( $container ) {
			if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
				return;
			}

			$container->get( Cache_Prime::class )->register();
		}, 0, 0 );
	}
}
