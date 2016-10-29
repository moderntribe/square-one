<?php

namespace Tribe\Project\Theme;

class Image_Wrap {

	public function hook() {

		add_filter( 'the_content', [ $this, 'customize_wp_image_non_captioned_output' ], 12, 1 );
		add_filter( 'the_content', [ $this, 'customize_wp_image_captioned_output' ], 12, 1 );
	}

	/**
	 * Customize WP non-captioned image output
	 * TODO: @backend code review this
	 *
	 * @param $html
	 *
	 * @return mixed
	 */
	public function customize_wp_image_non_captioned_output( $html ) {

		return preg_replace_callback( '/<p>((?:.(?!p>))*?)(<a[^>]*>)?\s*(<img[^>]+>)(<\/a>)?(.*?)<\/p>/is', function( $matches ) {

			/*
			Groups 	Regex 			 Description
					<p>			     starting <p> tag
			1	    ((?:.(?!p>))*?)	 match 0 or more of anything not followed by p>
					.(?!p>) 		 anything that's not followed by p>
					?: 			     non-capturing group.
					*?		         match the ". modified by p> condition" expression non-greedily
			2	    (<a[^>]*>)?		 starting <a> tag (optional)
					\s*			     white space (optional)
			3	    (<img[^>]+>)	 <img> tag
					\s*			     white space (optional)
			4	    (<\/a>)? 		 ending </a> tag (optional)
			5	    (.*?)<\/p>		 everything up to the final </p>
					i modifier 		 case insensitive
					s modifier		 allows . to match multiple lines (important for 1st and 5th group)
			*/

			// image and (optional) link: <a ...><img ...></a>
			$image = $matches[2] . $matches[3] . $matches[4];
			// content before and after image. wrap in <p> unless it's empty
			$content = trim( $matches[1] . $matches[5] );
			if ( $content ) {
				$content = '<p>' . $content . '</p>';
			}

			// move alignment classes to our non-caption image wrapper & remove from image
			// mimicks markup for captioned images
			preg_match( '#class\s*=\s*"[^"]*(alignnone|alignleft|aligncenter|alignright)[^"]*"#', $image,
				$alignment_match );
			$alignment = empty( $alignment_match[1] ) ? 'alignnone' : $alignment_match[1];

			$image = str_replace( $alignment_match[1] . ' ', '', $image );

			return sprintf( '<figure class="wp-image wp-image--no-caption %s">%s</figure>%s', $alignment, $image, $content );
		}, $html );

	}

	/**
	 * Customize WP captioned image output
	 * TODO: @backend code review this
	 *
	 * @param $html
	 *
	 * @return mixed
	 */
	public function customize_wp_image_captioned_output( $html ) {

		return preg_replace( '#wp-caption align#', 'wp-image wp-image--caption align', $html );
	}
}
