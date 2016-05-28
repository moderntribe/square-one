<?php

/**
 * Social Sharing
 */

class Social {

	/**
	 * Massages a link for use in social shares.
	 *
	 * @param string $url The url to parse
	 *
	 * @return string
	 */
	public function social_parse_url( $url = '' ) {

		$parsed = parse_url( $url );
		if ( empty( $parsed['scheme'] ) ) {
			$url = site_url( $url );
		}

		return $url;

	}

	/**
	 * Test location and returns an array of data containing title, link, and body + image
	 *
	 * @param array $enabled_networks An array of enabled social networks.
	 *
	 * @return array
	 */
	public function get_social_share_data( $enabled_networks = array() ) {

		global $wp_query;

		$data = array();

		if ( is_singular() || $wp_query->in_the_loop ) {

			global $post;

			$data['link']  = $this->social_parse_url( esc_url( get_permalink( $post->ID ) ) );
			$data['title'] = wp_strip_all_tags( esc_attr( get_the_title( $post ) ) );
			$data['body']  = esc_attr( $post->post_excerpt );

			// only hunt for a featured image if pinterest active, and if we are on single.
			// No pinterest for loops, because thats silly.
			if ( ! empty( $enabled_networks['pinterest'] ) && has_post_thumbnail( $post->ID ) ) {

				$image             = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'tribe-full' );
				$data['image_src'] = $image[0];

			}

		} elseif ( is_tax() || is_category() || is_tag() ) {

			$obj = get_queried_object();

			$data['link']  = $this->social_parse_url( get_term_link( $obj, $obj->taxonomy ) );
			$data['title'] = wp_strip_all_tags( esc_attr( $obj->name ) );
			$data['body']  = esc_attr( $obj->description );

		} elseif ( is_post_type_archive() ) {

			$obj = get_queried_object();

			$data['link']  = $this->social_parse_url( get_post_type_archive_link( $obj->name ) );
			$data['title'] = wp_strip_all_tags( esc_attr( $obj->label ) );
			$data['body']  = esc_attr( $obj->description );

		} elseif ( is_search() ) {

			$obj = get_search_query();

			$data['link']  = $this->social_parse_url( get_search_link( $obj ) );
			$data['title'] = 'Search Results: ' . esc_attr( $obj );

		}

		return $data;
	}

	/**
	 * Tests the network and returns a formatted a tag for that network with post/loop data injected into it.
	 *
	 * @param string $network The network key.
	 * @param array  $data    Share data supplied by get_social_share_data()
	 *
	 * @return string
	 */
	public function get_social_share_link( $network = '', $data = array(), $show_label = true ) {

		$class = $show_label ? '' : ' class="visual-hide"';

		switch ( $network ) {

			case "email":
				return sprintf( '<a class="icon icon-mail" href="mailto:?subject=%1$s&body=%2$s" title="%3$s"><span%4$s>%3$s</span></a>',
					$data['title'], $data['link'], 'Share through Email', $class );
				break;

			case "print":
				return sprintf( '<a class="icon icon-print" href="#" title="%1$s" onclick="window.print();return false;"><span%2$s>%1$s</span></a>',
					'Print this page', $class );
				break;

			case "google":
				return sprintf( '<a class="social-share-popup icon icon-google-plus" href="https://plus.google.com/share?url=%1$s" data-width="624" data-height="486" title="%2$s"><span%3$s>%2$s</span></a>',
					$data['link'], 'Share on Google+', $class );
				break;

			case "pinterest";
				if ( empty( $data['image_src'] ) ) {
					$link = '';
				} else {
					$link = sprintf( '<a class="social-share-popup icon icon-pinterest" href="http://pinterest.com/pin/create/button/?url=%1$s&amp;media=%2$s&amp;description=%3$s" data-width="624" data-height="300" title="%4$s"><span%5$s>%4$s</span></a>',
						$data['link'], $data['image_src'], $data['title'], 'Share on Pinterest', $class );
				}

				return $link;
				break;

			case "twitter":
				$text = substr( $data['title'], 0, 140 - strlen( $data['link'] ) - 4 );
				if ( $text !== $data['title'] ) {
					$text .= "...";
				}

				return sprintf( '<a class="social-share-popup icon icon-twitter" href="https://twitter.com/share?url=%1$s&text=%2$s" data-width="550" data-height="450" title="%3$s"><span%4$s>%3$s</span></a>',
					$data['link'], $text, 'Share on Twitter', $class );
				break;

			case "facebook":
				return sprintf( '<a class="social-share-popup icon icon-facebook" href="http://www.facebook.com/sharer.php?u=%1$s&t=%2$s" data-width="640" data-height="352" title="%3$s"><span%4$s>%3$s</span></a>',
					$data['link'], $data['title'], 'Share on Facebook', $class );
				break;

			case "linkedin":
				return sprintf( '<a class="social-share-popup icon icon-linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s" data-width="640" data-height="352" title="%3$s"><span%4$s>%3$s</span></a>',
					$data['link'], $data['title'], 'Share on LinkedIn', $class );
				break;

			default:
				return '';
				break;
		}

	}

	/**
	 * Loops over enabled networks and builds an array of formatted share links using get_social_share_link()
	 *
	 * @param array $enabled_networks An array of enabled social networks.
	 *
	 * @return array
	 */
	public function get_social_share_links( $enabled_networks = array(), $show_label = true ) {

		$data = $this->get_social_share_data( $enabled_networks );

		if ( empty( $data ) ) {
			return null;
		}

		$links = array();

		foreach ( $enabled_networks as $network ) {
			$links[] = $this->get_social_share_link( $network, $data, $show_label );
		}

		return $links;

	}

	/**
	 * Template tag that outputs share link html as long as some networks are enabled and we have data to work with.
	 *
	 * @param boolean $echo whether to echo or return
	 *
	 * @return string
	 */
	public function the_social_share_links( $enabled_networks = array(), $echo = true, $show_label = true ) {

		if ( empty( $enabled_networks ) ) {
			return null;
		}

		$links = $this->get_social_share_links( $enabled_networks, $show_label );

		if ( empty( $links ) ) {
			return null;
		}

		$social_share = sprintf( '<ul class="social-share-networks"><li>%s</li></ul>', implode( '</li><li>', array_filter( $links ) ) );

		if ( $echo ) {
			echo $social_share;
		} else {
			return $social_share;
		}

	}

}

new Social();
