<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\header\masthead;

use Tribe\Project\Templates\Factory_Method;

class Controller {
	use Factory_Method;

	public function logo(): string {
		return sprintf(
			'<div class="logo" data-js="logo"><a href="%s" rel="home">%s</a></div>',
			esc_url( home_url() ),
			get_bloginfo( 'blogname' )
		);
	}
}
