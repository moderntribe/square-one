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

		// Category
		elseif ( is_category() ) {
			$title = single_cat_title( '', false );
		}

		// Tags
		elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		}

		// Tax
		elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		}

		// Post Type Archive
		elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		}

		// Search
		elseif ( is_search() ) {
			$title = 'Search Results';
		}

		// 404
		elseif ( is_404() ) {
			$title = 'Page Not Found (404)';
		}

		else {
			$title = get_the_title();
		}
		
		return $title;
	}
}