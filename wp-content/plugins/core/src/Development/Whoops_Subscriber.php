<?php
declare( strict_types=1 );

namespace Tribe\Project\Development;

use Tribe\Libs\Container\Abstract_Subscriber;

class Whoops_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_action( 'init', function () {
			$this->container->get( \Whoops\Run::class )->register();
		}, - 10, 0 );
	}
}
