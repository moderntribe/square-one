<?php
/**
 * Template Tags: General Helpers
 *
 * @since tribe-square-one 1.0
 */


/**
 * Output string in an "attribute" format (lowercase, add dashes)
 *
 * @since tribe-square-one 1.0
 * @param $content
 * @return string
 */
function attributize( $content ) {
	if( $content )
	return strtolower( preg_replace( '/[^a-z\d]+/i', '-', $content ) );
}


/**
 * Output page ID, based on a page slug
 *
 * @since tribe-square-one 1.0
 * @param $slug
 */
function get_page_id_from_slug( $slug ) {
    $page = get_page_by_path( $slug );
    if( ! empty( $page ) )
    	return $page->ID;
}


/**
 * Output the first page ID for the page using the specified page template
 * Really only useful in the case where one page uses a specific page template
 *
 * @since tribe-square-one 1.0
 * @param $template
 */
function get_page_template_ID( $template ) {
	$page_ID = '';
	$args = array( 
		'meta_key'   => '_wp_page_template',
   		'meta_value' => $template		
	);
	$pages = get_pages( $args );
	foreach ( $pages as $page ) {
		$page_ID = $page->ID;
		break;
	}
	return $page_ID;
}

