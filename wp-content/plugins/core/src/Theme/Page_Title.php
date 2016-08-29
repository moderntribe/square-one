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
			$title = 'Blog';
		}

		// Search
		elseif ( is_search() ) {
			$title = 'Search Results';
		}

		// 404
		elseif ( is_404() ) {
			$title = 'Page Not Found (404)';
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
