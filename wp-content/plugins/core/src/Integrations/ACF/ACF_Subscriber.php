<?php declare(strict_types=1);

namespace Tribe\Project\Integrations\ACF;

use Tribe\Libs\Container\Abstract_Subscriber;

class ACF_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_action( 'acf/render_field', function ( $field ): void {
			$this->container->get( Max_Length_Counter::class )->add_counter_div( (array) $field );
		} );

		add_action( 'acf/input/admin_footer', function (): void {
			$this->container->get( Max_Length_Counter::class )->add_counter_js();
		} );
	}

}
