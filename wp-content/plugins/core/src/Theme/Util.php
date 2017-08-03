<?php

namespace Tribe\Project\Theme;

abstract class Util {

	/**
	 * Common use is trimming the_content from panels or some such
	 *
	 * @param array $options
	 * @return string
	 */
	public static function get_clean_truncated_html( $options = [] ) {
		$defaults = [
			'content' => '',
			'length'  => 55,
			'more'    => null,
			'autop'   => true,
		];

		$args = wp_parse_args( $options, $defaults );
		$result = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $args['content'] ) ), $args['length'], $args['more'] );

		if ( $args['autop'] ) {
			$result = wpautop( $result );
		}

		return $result;
	}

	/**
	 * Convert an array into an HTML class attribute string
	 *
	 * @param array $classes
	 * @param bool  $attribute
	 *
	 * @return string
	 */
	public static function class_attribute( $classes, $attribute = true ) {
		if ( empty( $classes ) ) {
			return '';
		}

		return sprintf(
			'%s%s%s',
			$attribute ? ' class="' : '',
			implode( ' ', array_unique( $classes ) ),
			$attribute ? '"' : ''
		);
	}

	/**
	 * Make a best-effort at extracting the file extension from a URL
	 *
	 * @param $url
	 *
	 * @return mixed|null
	 */
	public static function file_extension( $url ) {

		$extension = null;

		if ( empty( $url ) ) {
			return $extension;
		}

		$path = parse_url( $url, PHP_URL_PATH );

		if ( ! empty( $path ) ) {
			$extension = pathinfo( $path, PATHINFO_EXTENSION );
		}

		return $extension;
	}

	/**
	 * Output the first page ID for the page using the specified page template
	 * Really only useful in the case where one page uses a specific page template
	 *
	 * @param $template
	 *
	 * @return int
	 */
	public static function get_page_template_ID( $template ) {
		$args = [
			'meta_key'   => '_wp_page_template',
			'meta_value' => $template,
		];
		$pages = get_pages( $args );

		if ( empty( $pages ) ) {
		    return 0;
		}

		return $pages[0]->ID;
	}

}
