<?php
declare( strict_types=1 );

namespace Tribe\Project\Admin;

use Tribe\Project\Admin\Resources\Scripts;
use Tribe\Project\Admin\Resources\Styles;
use Tribe\Libs\Container\Abstract_Subscriber;

class Admin_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_action( 'admin_enqueue_scripts', function() {
			$this->container->get( Scripts::class )->enqueue_scripts();
			$this->container->get( Styles::class )->enqueue_styles();
		}, 10, 0 );

		// several 3rd-party plugins trigger errors in the admin due to use of deprecated hooks
		add_filter( 'deprecated_hook_trigger_error', '__return_false' );
	}

}
