<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Subscriber_Interface;

class Templates_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		require_once( dirname( __DIR__, 2 ) . '/functions/templates.php' );
	}
}
