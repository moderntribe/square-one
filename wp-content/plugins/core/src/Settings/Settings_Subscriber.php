<?php
declare( strict_types=1 );

namespace Tribe\Project\Settings;

use Tribe\Libs\Container\Abstract_Subscriber;

class Settings_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_action( 'init', function () {
			$this->container->get( General::class )->hook();
		}, 0, 0 );
	}
}
