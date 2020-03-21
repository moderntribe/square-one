<?php
declare( strict_types=1 );

namespace Tribe\Project\Development;

use Psr\Container\ContainerInterface;
use Tribe\Project\Container\Subscriber_Interface;

class Whoops_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		add_action( 'init', function () use ( $container ) {
			$container->get( \Whoops\Run::class )->register();
		}, - 10, 0 );
	}
}
