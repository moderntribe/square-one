<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\head;

use Tribe\Project\Templates\Factory_Method;

class Controller {
	use Factory_Method;

	public function name(): string {
		return get_bloginfo( 'name' );
	}

	public function pingback_url(): string {
		return get_bloginfo( 'pingback_url' );
	}

	public function page_title(): string {
		if ( is_front_page() ) {
			return '';
		}

		// Blog
		if ( is_home() ) {
			return __( 'Blog', 'tribe' );
		}

		// Search
		if ( is_search() ) {
			return __( 'Search Results', 'tribe' );
		}

		// 404
		if ( is_404() ) {
			return __( 'Page Not Found (404)', 'tribe' );
		}

		// Singular
		if ( is_singular() ) {
			return get_the_title();
		}

		// Archives
		return get_the_archive_title();
	}
}
