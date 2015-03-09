<?php
/**
 * Template Tags: Content Output
 */


/**
 * Output proper title
 *
 * @since tribe-square-one 1.0
 * @return string
 */
function the_page_title() {

	if( is_front_page() || is_single() )
		return;

	$title = '';

	// Blog
	if ( is_home() )
		$title = 'Blog';

	// Category
	elseif ( is_category() )
		$title = single_cat_title( '', false );

	// Tags
	elseif ( is_tag() )
		$title = single_tag_title( '', false );

	// Tax
	elseif ( is_tax() )
		$title = single_term_title( '', false );

	// Post Type Archive
	elseif( is_post_type_archive() )
		$title = post_type_archive_title( '', false );

	// Single
	elseif( is_single() )
		$title = get_post_type_object( get_post_type() )->labels->name;

	// Search
	elseif( is_search() )
		$title = 'Search Results';

	// 404
	elseif( is_404() )
		$title = 'Page Not Found (404)';

	else
		$title = get_the_title();

	if ( ! empty( $title ) )
		echo '<h1 class="h1 page-title" itemprop="name">'. $title .'</h1>';

}

