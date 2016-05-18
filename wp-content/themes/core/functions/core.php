<?php
/**
 * Functions: Theme Core
 *
 * Customizing WordPress options for our theme
 *
 * @since core 1.0
 */


/**
 * Option: removes default link from uploaded 
 */

add_filter( 'pre_option_image_default_link_type', function() { return 'none'; } );

