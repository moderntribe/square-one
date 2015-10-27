<?php
/**
 * Functions: Theme Core
 *
 * Customizing WordPress options & support for our theme
 *
 * @since tribe-square-one 1.0
 */


/**
 * Option: removes default link from uploaded 
 */

add_filter( 'pre_option_image_default_link_type', function() { return 'none'; } );


/**
 * Option: enable gzip compressions for WordPress
 */

add_filter( 'pre_option_gzipcompression', function() { return 1; } );


/**
 * Supports: enable Featured Images
 */

add_theme_support( 'post-thumbnails' );

/**
 * Supports: enable Document Title Tag
 */

add_theme_support( 'title-tag' );


/**
 * Support: switch core WordPress markup to output valid HTML5
 */

add_theme_support( 'html5', array(
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption'
) );