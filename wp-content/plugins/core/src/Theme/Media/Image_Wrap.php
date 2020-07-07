<?php

namespace Tribe\Project\Theme\Media;

class Image_Wrap {

	/**
	 * Customize WP non-captioned image output
	 * TODO: @backend code review this
	 *
	 * @param $html
	 *
	 * @return mixed
	 * @filter the_content
	 */
	public function customize_wp_image_non_captioned_output( $html ) {

		if ( ! is_singular() && ! in_the_loop() && ! is_main_query() ) {
			return $html;
		}

		return preg_replace_callback( '/<p>((?:.(?!p>))*?)(<a[^>]*>)?\s*(<img[^>]+>)(<\/a>)?(.*?)<\/p>/i', function ( $matches ) {

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
			preg_match( '#class\s*=\s*"[^"]*(alignnone|alignleft|aligncenter|alignright)[^"]*"#', $image, $alignment_match );
			$alignment = empty( $alignment_match[1] ) ? 'alignnone' : $alignment_match[1];

			$image = empty( $alignment_match[1] ) ? $image : str_replace( $alignment_match[1], '', $image );

			return sprintf( '<figure class="wp-image wp-image--no-caption %s">%s</figure>%s', $alignment, $image, $content );
		}, $html );
	}

	/**
	 * Customize WP captioned image output
	 *
	 * @param $html
	 *
	 * @return mixed
	 * @filter the_content
	 */
	public function customize_wp_image_captioned_output( $html ) {
		if ( ! is_singular() && ! in_the_loop() && ! is_main_query() ) {
			return $html;
		}

		/*
		 * preg_replace() Patterns
		 *
		 * Pattern #1:
		 * Update any <figcaption> whose parent <figure> has `style="width: 000px"` applied by applying the same
		 * style attribute to the <figcaption> tag.
		 *
		 * Example: http://p.tri.be/gK8px/3zENyNPO becomes: http://p.tri.be/VS5DMO/y5RMRKVc
		 *
		 * Group    Regex                                       Description
		 *          <figure                                     Start the figure tag match
		 * 1        ([^>]+?(style="width:\s*[\d]+px;?")[^>]*?)  All of the figure tag's attributes
		 *          [^>]+?                                      Any attributes before `style...`
		 * 2        (style="width:\s*[\d]+px;?")                The style attribute. Note this is very specific to the auto-generated style attribute that WP applies.
		 *          [^>]*?                                      Any attributes after `style...`
		 *          >\s*                                        Close the figure tag and account for any whitespace
		 * 3        (<img[^>]+>)                                The image tag
		 *          \s*                                         Account for any whitespace between the image tag and figcaption tag
		 *          <figcaption                                 Start the figcaption tag
		 * 4        ([^>]+)                                     All of the figcaption tag's attributes
		 *          >                                           close the figcaption tag
		 * 5        (.*?)                                       The content of the figcaption tag
		 *          </figcaption></figure>                      Close the figcaption and figure tags
		 *          i modifier                                  Case insensitive
		 *          s modifier                                  allows . to match multiple lines (important for 5th group)
		 *
		 * Pattern #2:
		 * Replace WP's default CSS <figure> classes with our BEMified classes
		 */

		$patterns = [
			'#<figure([^>]+?(style="width:\s*[\d]+px;?")[^>]*?)>\s*(<img[^>]+>)\s*<figcaption([^>]+)>(.*?)</figcaption></figure>#is',
			'#wp-caption align#',
		];

		$replacements = [
			'<figure$1>$3<figcaption$4 $2>$5</figcaption></figure>',
			'wp-image wp-image--caption align',
		];

		return preg_replace( $patterns, $replacements, $html );
	}
}
