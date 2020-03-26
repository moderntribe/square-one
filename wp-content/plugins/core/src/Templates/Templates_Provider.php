<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

use Psr\Container\ContainerInterface;

class Templates_Provider {

	public function register( ContainerInterface $container ): void {
		require_once( dirname( __DIR__, 2 ) . '/functions/templates.php' );
	}
}
