<?php


namespace Tribe\Project\Theme;


class Oembed_Wrap {
	public function hook() {
		add_filter( 'embed_oembed_html', [ $this, 'customize_embed_oembed_html' ], 99, 4 );
	}

	/**
	 * Add wrapper around embeds to setup CSS for embed aspect ratios
	 */
	function customize_embed_oembed_html( $html, $url, $attr, $post_id ) {
		return sprintf( '<div class="wp-embed"><div class="wp-embed-wrap">%s</div></div>', $html );
	}
}
