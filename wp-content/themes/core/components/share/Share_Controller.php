<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\share;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Theme\Config\Image_Sizes;

/**
 * Class Search
 */
class Share_Controller extends Abstract_Controller {

	public const EMAIL     = 'email';
	public const FACEBOOK  = 'facebook';
	public const LABELED   = 'labeled';
	public const LINKEDIN  = 'linkedin';
	public const NETWORKS  = 'networks';
	public const PINTEREST = 'pinterest';
	public const PRINT     = 'print';
	public const TWITTER   = 'twitter';

	/**
	 * @var string[]
	 */
	private array $networks;
	private bool $labeled;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->labeled  = (bool) $args[ self::LABELED ];
		$this->networks = (array) $args[ self::NETWORKS ];
	}

	/**
	 * Loops over enabled networks and builds an array of formatted share links
	 *
	 * @return array
	 */
	public function get_links(): array {
		$data = $this->get_data();

		if ( empty( $data ) ) {
			return [];
		}

		$links = [];

		foreach ( $this->networks as $network ) {
			$link = $this->build_link( $network, $data );
			if ( empty( $link ) ) {
				continue;
			}
			$links[] = $link;
		}

		return $links;
	}

	protected function defaults(): array {
		return [
			self::LABELED  => false,
			self::NETWORKS => [
				self::EMAIL,
				self::PRINT,
				self::PINTEREST,
				self::TWITTER,
				self::FACEBOOK,
				self::LINKEDIN,
			],
		];
	}


	/**
	 * Test location and returns an array of data containing title, link, and body + image
	 *
	 * @return array
	 */
	private function get_data(): array {
		$data = [];

		if ( is_singular() || in_the_loop() ) {
			global $post;

			$data['link']  = $this->normalize_url( get_permalink( $post->ID ) );
			$data['title'] = wp_strip_all_tags( esc_attr( get_the_title( $post ) ) );
			$data['body']  = esc_attr( $post->post_excerpt );

			// only hunt for a featured image if pinterest active, and if we are on single.
			// No pinterest for loops, because thats silly.
			if ( in_array( self::PINTEREST, $this->networks, true ) && has_post_thumbnail( $post->ID ) ) {
				$image             = wp_get_attachment_image_src(
					get_post_thumbnail_id( $post->ID ),
					Image_Sizes::CORE_FULL
				);
				$data['image_src'] = $image[0];
			}
		} elseif ( is_tax() || is_category() || is_tag() ) {
			$obj = get_queried_object();

			$data['link']  = $this->normalize_url( get_term_link( $obj, $obj->taxonomy ) );
			$data['title'] = wp_strip_all_tags( esc_attr( $obj->name ) );
			$data['body']  = esc_attr( $obj->description );
		} elseif ( is_post_type_archive() ) {
			$obj = get_queried_object();

			$data['link']  = $this->normalize_url( get_post_type_archive_link( $obj->name ) );
			$data['title'] = wp_strip_all_tags( esc_attr( $obj->label ) );
			$data['body']  = esc_attr( $obj->description );
		} elseif ( is_search() ) {
			$query = get_search_query();

			$data['link'] = $this->normalize_url( get_search_link( $query ) );
			/* translators: %s: Search results. */
			$data['title'] = sprintf( esc_html__( 'Search Results: %s', 'tribe' ), esc_attr( $query ) );
		}

		return $data;
	}


	/**
	 * Tests the network and returns a formatted a tag for that network with post/loop data injected into it.
	 *
	 * @param string $network The network key.
	 * @param array  $data Share data supplied by get_social_share_data()
	 *
	 * @return array
	 */
	private function build_link( string $network, array $data ): array {

		switch ( $network ) {
			case self::EMAIL:
				$label = esc_html__( 'Share through Email', 'tribe' );
				$link  = sprintf(
					'mailto:?subject=%1$s&body=%2$s',
					urlencode( $data['title'] ),
					urlencode( esc_url_raw( $data['link'] ) )
				);
				$icon  = 'icon-mail';

				return $this->build_link_args( $link, $label, $icon );

			case self::PRINT:
				$label      = esc_html__( 'Print this page', 'tribe' );
				$link       = '#';
				$icon       = 'icon-print';
				$attributes = [ 'onclick' => 'window.print();return false;' ];

				return $this->build_link_args( $link, $label, $icon, $attributes );

			case self::PINTEREST:
				if ( empty( $data['image_src'] ) ) {
					return [];
				}

				$label      = esc_html__( 'Share on Pinterest', 'tribe' );
				$link       = sprintf(
					'https://pinterest.com/pin/create/button/?url=%1$s&amp;media=%2$s&amp;description=%3$s',
					urlencode( esc_url_raw( $data['link'] ) ),
					urlencode( esc_url_raw( $data['image_src'] ) ),
					urlencode( $data['title'] )
				);
				$icon       = 'icon-pinterest';
				$attributes = [
					'data-js'     => 'social-share-popup',
					'data-width'  => '624',
					'data-height' => '300',
				];

				return $this->build_link_args( $link, $label, $icon, $attributes );

			case self::TWITTER:
				$character_limit = 280;
				$text            = substr( $data['title'], 0, $character_limit - strlen( $data['link'] ) - 4 );
				if ( $text !== $data['title'] ) {
					$text .= "...";
				}

				$label      = esc_html__( 'Share on Twitter', 'tribe' );
				$link       = sprintf(
					'https://twitter.com/share?url=%1$s&text=%2$s',
					urlencode( esc_url_raw( $data['link'] ) ),
					urlencode( $text )
				);
				$icon       = 'icon-twitter';
				$attributes = [
					'data-js'     => 'social-share-popup',
					'data-width'  => '550',
					'data-height' => '450',
				];

				return $this->build_link_args( $link, $label, $icon, $attributes );

			case self::FACEBOOK:
				$label      = esc_html__( 'Share on Facebook', 'tribe' );
				$link       = sprintf(
					'https://www.facebook.com/sharer.php?u=%1$s&t=%2$s',
					urlencode( esc_url_raw( $data['link'] ) ),
					urlencode( $data['title'] )
				);
				$icon       = 'icon-facebook';
				$attributes = [
					'data-js'     => 'social-share-popup',
					'data-width'  => '640',
					'data-height' => '352',
				];

				return $this->build_link_args( $link, $label, $icon, $attributes );

			case self::LINKEDIN:
				$label      = esc_html__( 'Share on LinkedIn', 'tribe' );
				$link       = sprintf(
					'https://www.linkedin.com/sharing/share-offsite/?url=%1$s',
					urlencode( esc_url_raw( $data['link'] ) ),
				);
				$icon       = 'icon-linkedin';
				$attributes = [
					'data-js'     => 'social-share-popup',
					'data-width'  => '640',
					'data-height' => '352',
				];

				return $this->build_link_args( $link, $label, $icon, $attributes );

			default:
				return [];
		}
	}

	private function build_link_args(
		string $url = '',
		string $label = '',
		string $icon = '',
		array $attributes = []
	): array {
		$classes = [ 'social-share-networks__anchor', 'icon' ];
		if ( $icon ) {
			$classes[] = $icon;
		}
		$attributes['title'] = $label;

		return [
			Link_Controller::CLASSES => $classes,
			Link_Controller::URL     => $url,
			Link_Controller::ATTRS   => $attributes,
			Link_Controller::CONTENT => $this->link_text_component( $label ),
		];
	}

	private function link_text_component( string $label ): string {
		$classes = $this->labeled ? [] : [ 'u-visually-hidden' ];

		return tribe_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'span',
			Text_Controller::CLASSES => $classes,
			Text_Controller::CONTENT => $label,
		] );
	}

	/**
	 * Massage a link for use in social shares.
	 *
	 * @param mixed $url The url to parse.
	 *
	 * @return string
	 */
	private function normalize_url( $url ): string {

		if ( ! is_string( $url ) ) {
			return '';
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
