<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\content\search_form;

use Tribe\Project\Templates\Factory_Method;

class Controller {
	use Factory_Method;

	public function action(): string {
		return get_home_url();
	}

	public function render_button(): void {
		get_template_part( 'components/button/button', null, [
			'content' => __( 'Search', 'tribe' ),
			'classes' => [ 'c-button' ],
			'type'    => 'submit',
			'attrs'   => [
				'name'  => 'submit',
				'value' => __( 'Search', 'tribe' ),
			],
		] );
	}
}
