<?php
/**
 * Functions: Theme Core
 *
 * Customizing WordPress options for our theme
 *
 * @since core 1.0
 */


// Remove WP SEO json-ld output
add_filter( 'wpseo_json_ld_output', '__return_false' );