<?php

namespace Tribe\Project\Theme;

class Page_Title {

	/**
	 * @return string
	 */
	public function get_title() {

		if ( is_front_page() ) {
			return '';
		}

		// Blog
		if ( is_home() ) {
			$title = __( 'Blog', 'tribe' );
		}

		// Search
		elseif ( is_search() ) {
			$title = __( 'Search Results', 'tribe' );
		}

		// 404
		elseif ( is_404() ) {
			$title = __( 'Page Not Found (404)', 'tribe' );
		}

		// Singular
		elseif ( is_singular() ) {
			$title = get_the_title();
		}

		// Archives
		else {
			$title = get_the_archive_title();
		}

		return $title;
	}
}
