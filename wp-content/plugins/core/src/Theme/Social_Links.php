<?php


namespace Tribe\Project\Theme;


class Social_Links {
	private $networks = [ ];
	private $labeled  = true;

	public function __construct( array $networks, $labeled = true ) {
		$this->networks = $networks;
		$this->labeled = (bool)$labeled;
	}

	/**
	 * Loops over enabled networks and builds an array of formatted
	 * share links
	 **
	 * @return array
	 */
	public function get_links() {

		$data = $this->get_data();

		if ( empty( $data ) ) {
			return [];
		}

		$links = [ ];

		foreach ( $this->networks as $network ) {
			$links[] = $this->build_link( $network, $data );
		}

		return $links;

	}

	/**
	 * @param array $links
	 * @return string
	 */
	public function format_links( array $links ) {
		$links = array_filter( $links );
		if ( empty( $links ) ) {
			return '';
		}
		$links = array_map( function( $link ) {
			return '<li>' . $link . '</li>';
		}, $links );
		return '<ul class="social-share-networks">' . implode( $links ) . '</ul>';
	}


	/**
	 * Test location and returns an array of data containing title, link, and body + image
	 *
	 * @return array
	 */
	private function get_data() {

		global $wp_query;

		$data = [ ];

		if ( is_singular() || $wp_query->in_the_loop ) {

			global $post;

			$data[ 'link' ] = $this->normalize_url( get_permalink( $post->ID ) );
			$data[ 'title' ] = wp_strip_all_tags( esc_attr( get_the_title( $post ) ) );
			$data[ 'body' ] = esc_attr( $post->post_excerpt );

			// only hunt for a featured image if pinterest active, and if we are on single.
			// No pinterest for loops, because thats silly.
			if ( in_array( 'pinterest', $this->networks ) && has_post_thumbnail( $post->ID ) ) {

				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'tribe-full' );
				$data[ 'image_src' ] = $image[ 0 ];

			}

		} elseif ( is_tax() || is_category() || is_tag() ) {

			$obj = get_queried_object();

			$data[ 'link' ] = $this->normalize_url( get_term_link( $obj, $obj->taxonomy ) );
			$data[ 'title' ] = wp_strip_all_tags( esc_attr( $obj->name ) );
			$data[ 'body' ] = esc_attr( $obj->description );

		} elseif ( is_post_type_archive() ) {

			$obj = get_queried_object();

			$data[ 'link' ] = $this->normalize_url( get_post_type_archive_link( $obj->name ) );
			$data[ 'title' ] = wp_strip_all_tags( esc_attr( $obj->label ) );
			$data[ 'body' ] = esc_attr( $obj->description );

		} elseif ( is_search() ) {

			$query = get_search_query();

			$data[ 'link' ] = $this->normalize_url( get_search_link( $query ) );
			$data[ 'title' ] = sprintf( __( 'Search Results: %s', 'tribe' ), esc_attr( $query ) );

		}

		return $data;
	}


	/**
	 * Tests the network and returns a formatted a tag for that network with post/loop data injected into it.
	 *
	 * @param string $network    The network key.
	 * @param array  $data       Share data supplied by get_social_share_data()
	 * @return string
	 */
	private function build_link( $network, $data ) {

		$class = $this->labeled ? '' : ' class="visual-hide"';

		switch ( $network ) {

			case "email":
				return sprintf(
					'<a class="icon icon-mail" href="mailto:?subject=%1$s&body=%2$s" title="%3$s"><span%4$s>%3$s</span></a>',
					urlencode( $data[ 'title' ] ),
					urlencode( esc_url_raw( $data[ 'link' ] ) ),
					__( 'Share through Email', 'tribe' ),
					$class
				);

			case "print":
				return sprintf(
					'<a class="icon icon-print" href="#" title="%1$s" onclick="window.print();return false;"><span%2$s>%1$s</span></a>',
					__( 'Print this page', 'tribe' ),
					$class
				);

			case "google":
				return sprintf(
					'<a class="social-share-popup icon icon-google-plus" href="https://plus.google.com/share?url=%1$s" data-width="624" data-height="486" title="%2$s"><span%3$s>%2$s</span></a>',
					urlencode( esc_url_raw( $data[ 'link' ] ) ),
					__( 'Share on Google+', 'tribe' ),
					$class
				);

			case "pinterest";
				if ( empty( $data[ 'image_src' ] ) ) {
					$link = '';
				} else {
					$link = sprintf(
						'<a class="social-share-popup icon icon-pinterest" href="http://pinterest.com/pin/create/button/?url=%1$s&amp;media=%2$s&amp;description=%3$s" data-width="624" data-height="300" title="%4$s"><span%5$s>%4$s</span></a>',
						urlencode( esc_url_raw( $data[ 'link' ] ) ),
						urlencode( esc_url_raw( $data[ 'image_src' ] ) ),
						urlencode( $data[ 'title' ] ),
						__( 'Share on Pinterest', 'tribe' ),
						$class
					);
				}

				return $link;

			case "twitter":
				$text = substr( $data[ 'title' ], 0, 140 - strlen( $data[ 'link' ] ) - 4 );
				if ( $text !== $data[ 'title' ] ) {
					$text .= "...";
				}

				return sprintf(
					'<a class="social-share-popup icon icon-twitter" href="https://twitter.com/share?url=%1$s&text=%2$s" data-width="550" data-height="450" title="%3$s"><span%4$s>%3$s</span></a>',
					urlencode( esc_url( $data[ 'link' ] ) ),
					urlencode( $text ),
					__( 'Share on Twitter', 'tribe' ),
					$class
				);

			case "facebook":
				return sprintf(
					'<a class="social-share-popup icon icon-facebook" href="http://www.facebook.com/sharer.php?u=%1$s&t=%2$s" data-width="640" data-height="352" title="%3$s"><span%4$s>%3$s</span></a>',
					urlencode( esc_url( $data[ 'link' ] ) ),
					urlencode( $data[ 'title' ] ),
					__( 'Share on Facebook', 'tribe' ),
					$class
				);

			case "linkedin":
				return sprintf(
					'<a class="social-share-popup icon icon-linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s" data-width="640" data-height="352" title="%3$s"><span%4$s>%3$s</span></a>',
					urlencode( esc_url( $data[ 'link' ] ) ),
					urlencode( $data[ 'title' ] ),
					__( 'Share on LinkedIn', 'tribe' ),
					$class
				);

			default:
				return '';
		}

	}

	/**
	 * Massage a link for use in social shares.
	 *
	 * @param string $url The url to parse
	 *
	 * @return string
	 */
	private function normalize_url( $url ) {

		if ( ! is_scalar( $url ) ) {
			$url = '';
		}

		/*
		 * If we've somehow ended up with a malformed or partial
		 * URL, return the home page of the site
		 */
		$scheme = parse_url( $url, PHP_URL_SCHEME );
		if ( empty( $scheme ) ) {
			$url = home_url( $url );
		}

		return $url;

	}
}