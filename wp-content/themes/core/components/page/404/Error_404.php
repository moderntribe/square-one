<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Page;

use Tribe\Project\Components\Component;

class Error_404 extends Component {

	public const TITLE   = 'error_404_browser_title';
	public const CONTENT = 'error_404_browser_content';

	protected function defaults(): array {
		return [
			self::TITLE   => $this->get_404_page_title(),
			self::CONTENT => $this->get_404_page_content(),
		];
	}

	protected function get_404_page_title(): string {
		return __( 'Whoops! We are having trouble locating your page!', 'tribe' );
	}

	protected function get_404_page_content(): string {
		return __( "If you're lost or have reached this page in error, our apologies. Please use the navigation menu or the links in the footer to find your way through the site. Please e-mail us if you have any questions.", 'tribe' );
	}
}
