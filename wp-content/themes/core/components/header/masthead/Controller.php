<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\header\masthead;

use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {

	public function logo(): string {
		return sprintf(
			'<div class="logo" data-js="logo"><a href="%s" rel="home">%s</a></div>',
			esc_url( home_url() ),
			get_bloginfo( 'blogname' )
		);
	}
}
