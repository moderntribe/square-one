<?php
declare( strict_types=1 );

namespace Tribe\Project\Admin;

use Psr\Container\ContainerInterface;
use Tribe\Project\Admin\Resources\Scripts;
use Tribe\Project\Admin\Resources\Styles;
use Tribe\Libs\Container\Subscriber_Interface;

class Admin_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		add_action( 'admin_enqueue_scripts', function() use ( $container ) {
			$container->get( Scripts::class )->enqueue_scripts();
			$container->get( Styles::class )->enqueue_styles();
		}, 10, 0 );

		// several 3rd-party plugins trigger errors in the admin due to use of deprecated hooks
		add_filter( 'deprecated_hook_trigger_error', '__return_false' );
	}

}
