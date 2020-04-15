<?php
declare( strict_types=1 );

namespace Tribe\Project\Admin;

use Tribe\Libs\Container\Abstract_Subscriber;

class Admin_Subscriber extends Abstract_Subscriber {
	public function register(): void {

		// several 3rd-party plugins trigger errors in the admin due to use of deprecated hooks
		add_filter( 'deprecated_hook_trigger_error', '__return_false' );
	}

}
