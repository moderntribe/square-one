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

		$url_parts = parse_url( $url );

		if ( ! empty( $url_parts['path'] ) ) {
			$extension = pathinfo( $url_parts['path'], PATHINFO_EXTENSION );
		}

		return $extension;
	}

	/**
	 * Output the first page ID for the page using the specified page template
	 * Really only useful in the case where one page uses a specific page template
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public static function get_page_template_ID( $template ) {
		$page_ID = '';
		$args    = [
			'meta_key'   => '_wp_page_template',
			'meta_value' => $template,
		];
		$pages   = get_pages( $args );
		if ( ! empty( $pages ) ) {
			foreach ( $pages as $page ) {
				$page_ID = $page->ID;
				break;
			}
		}

		return $page_ID;
	}

}
