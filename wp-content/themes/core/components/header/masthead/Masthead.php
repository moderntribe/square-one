<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Components\Component;

class Masthead extends Component {

	public const NAVIGATION = 'navigation';
	public const LOGO       = 'logo';
	public const SEARCH     = 'search';

	public function init() {
		$this->data['logo'] = $this->get_logo();
	}

	protected function get_logo() {
		return sprintf( '<div class="logo" data-js="logo"><a href="%s" rel="home">%s</a></div>', esc_url( home_url() ), get_bloginfo( 'blogname' ) );
	}
}
