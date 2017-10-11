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
	 * Convert an array of key => value pairs into an attributes string.
	 *
	 * @param $array
	 *
	 * @return string
	 */
	public static function array_to_attributes( $array ) {
		$attrs = array_map( function ( $key ) use ( $array ) {
			if ( is_bool( $array[ $key ] ) ) {
				return $array[ $key ] ? $key : '';
			}

			return $key . '=\'' . esc_attr( $array[ $key ] ) . '\'';
		}, array_keys( $array ) );

		return implode( ' ', $attrs );
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

	/**
	 * Returns an array of pagination links.
	 *
	 * @param bool $links_offset    - the max # of links to show on either side of the current page. False for all pages.
	 * @param bool $show_next_prev  - show links for next/prev page.
	 * @param bool $show_first_last - show links for first/last page.
	 * @param bool $show_ellipses   - show ellipses when pages exist beyond offset on either side of current page.
	 *
	 * @return array
	 */
	public static function get_pagination_links( $links_offset = false, $show_next_prev = true, $show_first_last = true, $show_ellipses = true ) {
		$links  = [];
		$values = [];

		global $wp_query;

		/** Stop execution if there's only 1 page */
		if ( $wp_query->max_num_pages <= 1 ) {
			return [];
		}

		$paged    = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		$max      = (int) $wp_query->max_num_pages;
		$is_first = $paged == 1;
		$is_last  = $paged == $max;

		if ( $links_offset ) {

			$links[] = $paged;

			for ( $i = max( 0, $paged - $links_offset ); $i < $paged; $i ++ ) {
				if ( $i == 0 ) {
					continue;
				}
				$links[] = $i;
			}

			for ( $i = $paged + 1; $i <= $paged + $links_offset; $i ++ ) {
				if ( $i > $max ) {
					continue;
				}
				$links[] = $i;
			}
		} else {
			for ( $i = 1; $i <= $max; $i ++ ) {
				$links[] = $i;
			}
		}

		if ( $show_first_last && ! $is_first ) {

			$values[] = [
				'url'     => get_pagenum_link( 1 ),
				'label'   => '<<',
				'active'  => false,
				'next'    => false,
				'prev'    => false,
				'classes' => [ 'first' ],
			];
		}

		if ( $show_next_prev && ! $is_first ) {
			$values[] = [
				'url'     => get_pagenum_link( $paged - 1 ),
				'label'   => '<',
				'active'  => false,
				'next'    => false,
				'prev'    => true,
				'classes' => [ 'prev' ],
			];
		}

		if ( $show_ellipses && ! $is_first && ! in_array( 1, $links ) ) {
			$values[] = [
				'url'     => '#',
				'label'   => '...',
				'active'  => false,
				'next'    => false,
				'prev'    => false,
				'classes' => [ 'ellipses' ],
			];
		}

		sort( $links );
		foreach ( $links as $link ) {
			$active   = $paged == $link;
			$values[] = [
				'url'     => get_pagenum_link( $link ),
				'label'   => $link,
				'active'  => $active,
				'next'    => false,
				'prev'    => false,
				'classes' => [],
			];
		}

		if ( $show_ellipses && ! in_array( $max, $links ) ) {
			$values[] = [
				'url'     => '#',
				'label'   => '...',
				'active'  => false,
				'next'    => false,
				'prev'    => false,
				'classes' => [ 'ellipses' ],
			];
		}

		if ( $show_next_prev && ! $is_last ) {
			$values[] = [
				'url'     => get_pagenum_link( $paged + 1 ),
				'label'   => '>',
				'active'  => false,
				'next'    => true,
				'prev'    => false,
				'classes' => [ 'next' ],
			];
		}

		if ( $show_first_last && ! $is_last ) {
			$values[] = [
				'url'     => get_pagenum_link( $max ),
				'label'   => '>>',
				'active'  => false,
				'next'    => false,
				'prev'    => false,
				'classes' => [ 'last' ],
			];
		}

		return $values;
	}

}
