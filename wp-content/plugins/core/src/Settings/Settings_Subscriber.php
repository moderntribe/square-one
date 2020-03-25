<?php
declare( strict_types=1 );

namespace Tribe\Project\Settings;

use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Subscriber_Interface;

class Settings_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		add_action( 'init', function () use ( $container ) {
			$container->get( General::class )->hook();
		}, 0, 0 );
	}
}
