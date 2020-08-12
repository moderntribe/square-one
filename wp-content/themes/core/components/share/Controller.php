<?php

namespace Tribe\Project\Templates\Components\share;


use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Theme\Config\Image_Sizes;

/**
 * Class Search
 */
class Controller extends Abstract_Controller {
	public const EMAIL     = 'email';
	public const PRINT     = 'print';
	public const PINTEREST = 'pinterest';
	public const TWITTER   = 'twitter';
	public const FACEBOOK  = 'facebook';
	public const LINKEDIN  = 'linkedin';

	/**
	 * @var array
	 */
	public $links;

	/**
	 * @var bool
	 */
	public $labeled;

	/**
	 * @var array
	 */
	protected $networks = [
		self::EMAIL,
		self::PRINT,
		self::PINTEREST,
		self::TWITTER,
		self::FACEBOOK,
		self::LINKEDIN,
	];

	/**
	 * Controller constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args          = wp_parse_args( $args, $this->defaults() );
		$this->labeled = (bool) $args[ 'labeled' ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			'labeled' => false,
		];
	}

	/**
	 * Loops over enabled networks and builds an array of formatted
	 * share links
	 **
	 *
	 * @return array
	 */
	public function get_links() {

		$data = $this->get_data();

		if ( empty( $data ) ) {
			return [];
		}

		$links = [];

		foreach ( $this->networks as $network ) {
			$links[] = $this->build_link( $network, $data );
		}

		return $links;
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

			$data[ 'link' ]  = $this->normalize_url( get_permalink( $post->ID ) );
			$data[ 'title' ] = wp_strip_all_tags( esc_attr( get_the_title( $post ) ) );
			$data[ 'body' ]  = esc_attr( $post->post_excerpt );

			// only hunt for a featured image if pinterest active, and if we are on single.
			// No pinterest for loops, because thats silly.
			if ( in_array( self::PINTEREST, $this->networks, true ) && has_post_thumbnail( $post->ID ) ) {

				$image               = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),
					Image_Sizes::CORE_FULL );
				$data[ 'image_src' ] = $image[ 0 ];

			}

		} elseif ( is_tax() || is_category() || is_tag() ) {

			$obj = get_queried_object();

			$data[ 'link' ]  = $this->normalize_url( get_term_link( $obj, $obj->taxonomy ) );
			$data[ 'title' ] = wp_strip_all_tags( esc_attr( $obj->name ) );
			$data[ 'body' ]  = esc_attr( $obj->description );

		} elseif ( is_post_type_archive() ) {

			$obj = get_queried_object();

			$data[ 'link' ]  = $this->normalize_url( get_post_type_archive_link( $obj->name ) );
			$data[ 'title' ] = wp_strip_all_tags( esc_attr( $obj->label ) );
			$data[ 'body' ]  = esc_attr( $obj->description );

		} elseif ( is_search() ) {

			$query = get_search_query();

			$data[ 'link' ]  = $this->normalize_url( get_search_link( $query ) );
			$data[ 'title' ] = sprintf( __( 'Search Results: %s', 'tribe' ), esc_attr( $query ) );

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
	private function build_link( $network, $data ) {

		switch ( $network ) {

			case self::EMAIL:
				$label = __( 'Share through Email', 'tribe' );
				$link  = sprintf( 'mailto:?subject=%1$s&body=%2$s', urlencode( $data[ 'title' ] ),
					urlencode( esc_url_raw( $data[ 'link' ] ) ) );
				$icon  = 'icon-mail';

				return $this->build_link_args( $link, $label, $icon );

			case self::PRINT:
				$label      = __( 'Print this page', 'tribe' );
				$link       = '#';
				$icon       = 'icon-print';
				$attributes = [ 'onclick' => 'window.print();return false;' ];

				return $this->build_link_args( $link, $label, $icon, $attributes );

			case self::PINTEREST:
				if ( empty( $data[ 'image_src' ] ) ) {
					return [];
				}

				$label      = __( 'Share on Pinterest', 'tribe' );
				$link       = sprintf( 'http://pinterest.com/pin/create/button/?url=%1$s&amp;media=%2$s&amp;description=%3$s',
					urlencode( esc_url_raw( $data[ 'link' ] ) ), urlencode( esc_url_raw( $data[ 'image_src' ] ) ),
					urlencode( $data[ 'title' ] ) );
				$icon       = 'icon-pinterest';
				$attributes = [
					'data-js'     => 'social-share-popup',
					'data-width'  => '624',
					'data-height' => '300',
				];

				return $this->build_link_args( $link, $label, $icon, $attributes );

			case self::TWITTER:
				$character_limit = 280;
				$text            = substr( $data[ 'title' ], 0, $character_limit - strlen( $data[ 'link' ] ) - 4 );
				if ( $text !== $data[ 'title' ] ) {
					$text .= "...";
				}

				$label      = __( 'Share on Twitter', 'tribe' );
				$link       = sprintf( 'https://twitter.com/share?url=%1$s&text=%2$s',
					urlencode( esc_url_raw( $data[ 'link' ] ) ), urlencode( $text ) );
				$icon       = 'icon-twitter';
				$attributes = [
					'data-js'     => 'social-share-popup',
					'data-width'  => '550',
					'data-height' => '450',
				];

				return $this->build_link_args( $link, $label, $icon, $attributes );

			case self::FACEBOOK:
				$label      = __( 'Share on Facebook', 'tribe' );
				$link       = sprintf( 'http://www.facebook.com/sharer.php?u=%1$s&t=%2$s',
					urlencode( esc_url_raw( $data[ 'link' ] ) ), urlencode( $data[ 'title' ] ) );
				$icon       = 'icon-facebook';
				$attributes = [
					'data-js'     => 'social-share-popup',
					'data-width'  => '640',
					'data-height' => '352',
				];

				return $this->build_link_args( $link, $label, $icon, $attributes );

			case self::LINKEDIN:
				$label      = __( 'Share on LinkedIn', 'tribe' );
				$link       = sprintf( 'http://www.linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s',
					urlencode( esc_url_raw( $data[ 'link' ] ) ), urlencode( $data[ 'title' ] ) );
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
		$attributes[ 'title' ] = $label;

		return [
			'classes' => $classes,
			'url'     => $url,
			'attrs'   => $attributes,
			'content' => $this->link_text_component( $label ),
		];
	}

	private function link_text_component( $label ): string {
		$classes = $this->labeled ? [] : [ 'u-visually-hidden' ];

		return tribe_template_part( 'component/text/text', null, [
			'tag'     => 'span',
			'classes' => $classes,
			'text'    => $label,
		] );
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
