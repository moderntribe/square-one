<?php


namespace Tribe\Project\Theme;


class Image_Wrap {
	public function hook() {
		add_filter( 'the_content', [ $this, 'customize_wp_image_output' ], 12, 1 );
	}

	/**
	 * Customize WP image output
	 */
	public function customize_wp_image_output( $html ) {

		$regex = '#((<\s*figure[^>]*?>)(.*?))?((<\s*a\s[^>]*?>)(.*?))?((<\s*img[^>]+)(src\s*=\s*"[^"]+")([^>]+>))((.*?)(</a>))?((.*?)(</figure>))?#i';
		$html = preg_replace_callback( $regex, [ $this, 'image_wrap_regex_callback' ], $html );
		return $html;

	}

	public function image_wrap_regex_callback( $matches ) {

		$full_match = $matches[ 0 ];
		$the_figure = $matches[ 2 ];
		$the_img = $matches[ 7 ];
		$the_img_src = $matches[ 9 ];

		$updated_image = str_replace( $the_img_src, $the_img_src, $the_img );

		if ( empty( $the_figure ) ) {
			$full_match = sprintf( '<figure class="wp-image-wrap">%s</figure>', $full_match );
		}

		return str_replace( $the_img, $updated_image, $full_match );

	}

}
