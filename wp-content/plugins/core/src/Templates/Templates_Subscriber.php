<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

use Tribe\Libs\Container\Abstract_Subscriber;

class Templates_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		require_once( dirname( __DIR__, 2 ) . '/functions/templates.php' );
	}
}
