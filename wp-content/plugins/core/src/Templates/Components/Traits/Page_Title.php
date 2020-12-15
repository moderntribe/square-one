<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Traits;

trait Page_Title {
	public function get_page_title(): string {
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
