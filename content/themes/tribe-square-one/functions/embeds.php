<?php
/**
 * Functions: Embeds
 *
 * Functions for handling embeds
 *
 * @since tribe-square-one 1.0
 */


add_filter( 'embed_oembed_html', 'customize_embed_oembed_html', 99, 4 );


/**
* Add wrapper around embeds to setup CSS for embed aspect ratios
*/

function customize_embed_oembed_html( $html, $url, $attr, $post_id ) {

	return '<div class="wp-embed"><div class="wp-embed-wrap">' . $html . '</div></div>';

}

