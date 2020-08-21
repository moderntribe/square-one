<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\header\subheader;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Traits\Page_Title;

class Controller extends Abstract_Controller {
	use Page_Title;

	public function get_title(): string {
		if ( empty( $this->get_page_title() ) ) {
			return '';
		}

		return tribe_template_part( 'components/text/text', null, [
			'tag'     => 'h1',
			'classes' => [ 'page-title', 'h1' ],
			'content' => $this->get_page_title(),
		] );
	}
}
