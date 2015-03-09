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

update_option( 'image_default_link_type', 'none' );


/**
 * Option: enable gzip compressions for WordPress
 */

update_option( 'gzipcompression', 1 );


/**
 * Supports: enable Featured Images
 */

add_theme_support( 'post-thumbnails' );


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