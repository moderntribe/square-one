<?php

namespace Tribe\Project\Templates\Components\image;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Image_Controller extends Abstract_Controller {
	public const ATTRS             = 'attrs';
	public const CLASSES           = 'classes';
	public const WRAPPER_TAG       = 'wrapper_tag';
	public const IMG_ID            = 'img_id';
	public const IMG_URL           = 'img_url';
	public const AS_BG             = 'as_bg';
	public const AUTO_SHIM         = 'auto_shim';
	public const AUTO_SIZES_ATTR   = 'auto_sizes_attr';
	public const EXPAND            = 'expand';
	public const HTML              = 'html';
	public const IMG_CLASSES       = 'img_classes';
	public const IMG_ATTRS         = 'img_attrs';
	public const IMG_ALT_TEXT      = 'img_alt_text';
	public const LINK_URL          = 'link_url';
	public const LINK_CLASSES      = 'link_classes';
	public const LINK_TARGET       = 'link_target';
	public const LINK_TITLE        = 'link_title';
	public const LINK_ATTRS        = 'link_attrs';
	public const PARENT_FIT        = 'parent_fit';
	public const SHIM              = 'shim';
	public const SRC               = 'src';
	public const SRC_SIZE          = 'src_size';
	public const SRCSET_SIZES      = 'srcset_sizes';
	public const SRCSET_SIZES_ATTR = 'srcset_sizes_attr';
	public const USE_HW_ATTR       = 'use_hw_attr';
	public const USE_LAZYLOAD      = 'use_lazyload';
	public const USE_SRCSET        = 'use_srcset';

	/** @var int ID of the WordPress attachment to use. Takes precedence over img_url */
	private int $img_id;

	/** @var string Image URL - generate markup for an image via its URL. Only applicable if img_url is empty. */
	private string $img_url;

	/** @var bool Generates a background image on a `<div>` instead of a traditional `<img>` */
	private bool $as_bg;

	/** @var bool If true, shim dir as set will be used, src_size will be used as filename, with png as file type. */
	private bool $auto_shim;

	/** @var bool If lazyloading the lib can auto create sizes attribute */
	private bool $auto_sizes_attr;

	/** @var int Expand attribute is the threshold used by lazysizes. Use negative to reveal once in viewport. */
	private int $expand;

	/** @var string Append an html string inside the wrapper. Useful for adding a `<figcaption>` or other markup after the image. */
	private string $html;

	/** @var array Pass classes for image tag. if lazyload is true class "lazyload" is auto added. */
	private array $img_classes;

	/** @var array Additional image attributes */
	private array $img_attrs;

	/** @var string Pass specific image alternate text. If not included, will default to image alt text and then title. */
	private string $img_alt_text;

	/** @var string Pass a link to wrap the image */
	private string $link_url;

	/** @var array Pass link classes */
	private array $link_classes;

	/** @var string Pass a link target */
	private string $link_target;

	/** @var string Pass a link title */
	private string $link_title;

	/** @var array Pass additional link attributes */
	private array $link_attrs;

	/** @var string If lazyloading this combines with object fit css and the object fit polyfill */
	private string $parent_fit;

	/** @var string Supply a manually specified shim for lazyloading. Will override auto_shim whether true/false. */
	private string $shim;

	/** @var bool Set to false to disable the src attribute. This is a fallback for non srcset browsers. */
	private bool $src;

	/** @var string This is the main src registered image size */
	private string $src_size;

	/** @var array This is registered sizes array for srcset */
	private array $srcset_sizes;

	/** @var string This is the srcset sizes attribute string used if auto is false */
	private string $srcset_sizes_attr;

	/** @var bool This will set the width and height attributes on the img to be half the original for retina/hdpi. Only for not lazyloading and when src exists. */
	private bool $use_hw_attr;

	/** @var bool Use lazyloading for this image? If `AS_BG` is true, `SRCSET_SIZES` must also be defined. */
	private bool $use_lazyload;

	/** @var bool Use srcset for this image? */
	private bool $use_srcset;

	/** @var string[] */
	private array $attrs;

	/** @var array Pass classes for figure wrapper. If as_bg is set true gets auto class of "lazyload". */
	private array $classes;

	/** @var string Html tag for the wrapper/background image container */
	private string $wrapper_tag;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->img_id            = (int) $args[ self::IMG_ID ];
		$this->img_url           = (string) $args[ self::IMG_URL ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->wrapper_tag       = (string) $args[ self::WRAPPER_TAG ];
		$this->as_bg             = (bool) $args[ self::AS_BG ];
		$this->auto_shim         = (bool) $args[ self::AUTO_SHIM ];
		$this->auto_sizes_attr   = (bool) $args[ self::AUTO_SIZES_ATTR ];
		$this->expand            = (int) $args[ self::EXPAND ];
		$this->html              = (string) $args[ self::HTML ];
		$this->img_classes       = (array) $args[ self::IMG_CLASSES ];
		$this->img_attrs         = (array) $args[ self::IMG_ATTRS ];
		$this->img_alt_text      = (string) $args[ self::IMG_ALT_TEXT ];
		$this->link_url          = (string) $args[ self::LINK_URL ];
		$this->link_classes      = (array) $args[ self::LINK_CLASSES ];
		$this->link_target       = (string) $args[ self::LINK_TARGET ];
		$this->link_title        = (string) $args[ self::LINK_TITLE ];
		$this->link_attrs        = (array) $args[ self::LINK_ATTRS ];
		$this->parent_fit        = (string) $args[ self::PARENT_FIT ];
		$this->shim              = (string) $args[ self::SHIM ];
		$this->src               = (bool) $args[ self::SRC ];
		$this->src_size          = (string) $args[ self::SRC_SIZE ];
		$this->srcset_sizes      = (array) $args[ self::SRCSET_SIZES ];
		$this->srcset_sizes_attr = (string) $args[ self::SRCSET_SIZES_ATTR ];
		$this->use_hw_attr       = (bool) $args[ self::USE_HW_ATTR ];
		$this->use_lazyload      = (bool) $args[ self::USE_LAZYLOAD ];
		$this->use_srcset        = (bool) $args[ self::USE_SRCSET ];
	}

	protected function defaults(): array {
		return [
			self::IMG_ID            => 0,
			self::IMG_URL           => '',
			self::ATTRS             => [],
			self::CLASSES           => [],
			self::WRAPPER_TAG       => 'figure',
			self::AS_BG             => false,
			self::AUTO_SHIM         => true,
			self::AUTO_SIZES_ATTR   => false,
			self::EXPAND            => 200,
			self::HTML              => '',
			self::IMG_CLASSES       => [],
			self::IMG_ATTRS         => [],
			self::IMG_ALT_TEXT      => '',
			self::LINK_URL          => '',
			self::LINK_CLASSES      => [],
			self::LINK_TARGET       => '',
			self::LINK_TITLE        => '',
			self::LINK_ATTRS        => [],
			self::PARENT_FIT        => 'width',
			self::SHIM              => '',
			self::SRC               => true,
			self::SRC_SIZE          => 'large',
			self::SRCSET_SIZES      => [],
			self::SRCSET_SIZES_ATTR => '(min-width: 1260px) 1260px, 100vw',
			self::USE_HW_ATTR       => false,
			self::USE_LAZYLOAD      => true,
			self::USE_SRCSET        => true,
		];
	}

	protected function required(): array {
		return [
			self::CLASSES      => [ 'c-image' ],
			self::IMG_CLASSES  => [ 'c-image__image' ],
			self::LINK_CLASSES => [ 'c-image__link' ],
		];
	}

	public function should_lazy_load(): bool {
		$missing_src = empty( $this->img_id ) && empty( $this->img_url );

		return $this->use_lazyload && ! $missing_src;
	}

	/**
	 * Get the component's image markup.
	 *
	 * Set to `<img />` if `AS_BG` is false and `<div>` if `AS_BG` is true.
	 *
	 * @return string
	 */
	public function get_image(): string {
		$classes = $this->img_classes;

		if ( $this->should_lazy_load() ) {
			$classes[] = 'lazyload';
		}

		if ( $this->as_bg ) {
			// <div classes attrs></div>
			$image = sprintf( '<div %s %s></div>', Markup_Utils::class_attribute( $classes ), $this->get_image_attributes() );
		} else {
			// <img classes attrs />
			$image = sprintf( '<img %s %s />', Markup_Utils::class_attribute( $classes ), $this->get_image_attributes() );
		}

		return $image;
	}

	/**
	 * Get the component's wrapper element markup tag.
	 *
	 * Defaults to `<figure>` if `AS_BG` is false and `<div>` if `AS_BG` is true.
	 *
	 * @return string
	 */
	public function get_wrapper_tag(): string {
		$tag = $this->wrapper_tag;

		if ( empty( $tag ) ) {
			$tag = $this->as_bg ? 'div' : 'figure';
		}

		return tag_escape( $tag );
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_link_classes(): string {
		return Markup_Utils::class_attribute( $this->link_classes );
	}

	public function get_link_attributes(): string {
		$attrs = $this->link_attrs;

		if ( ! empty( $this->link_target ) ) {
			$attrs['target'] = esc_attr( $this->link_target );
		}

		if ( $this->link_target === '_blank' ) {
			$attrs['rel'] = 'noopener';
		}

		return Markup_Utils::concat_attrs( $attrs );
	}

	public function has_link(): bool {
		return ! empty( $this->link_url );
	}

	public function get_link_url(): string {
		return $this->link_url;
	}

	/**
	 * Util to set item attributes for lazyload or not, bg or not.
	 *
	 * @return string
	 */
	public function get_image_attributes(): string {
		$attrs      = $this->img_attrs;
		$src        = '';
		$src_width  = 0;
		$src_height = 0;
		// we'll almost always set src, except if for some reason they wanted to only use srcset
		if ( $this->src ) {
			if ( $this->img_id ) {
				[ $src, $src_width, $src_height ] = wp_get_attachment_image_src( $this->img_id, $this->src_size )
					?: [ '', 0, 0, ];
			} else {
				$src = $this->img_url;
			}
		}

		// the alt text
		$alt_text = $this->img_alt_text;

		// Check for a specific alt meta value on the image post, otherwise fallback to the image post's title.
		if ( empty( $alt_text ) && $this->img_id ) {
			$alt_text = get_post_meta( $this->img_id, '_wp_attachment_image_alt', true ) ?: get_the_title( $this->img_id );
		}

		if ( $this->as_bg ) {
			$attrs['role']       = 'img';
			$attrs['aria-label'] = esc_attr( $alt_text );
		} else {
			$attrs['alt'] = esc_attr( $alt_text );
		}

		if ( $this->use_lazyload ) {

			// the expand attribute that controls threshold
			$attrs['data-expand'] = esc_attr( $this->expand );

			// the parent fit attribute if as_bg is not used.
			if ( ! $this->as_bg ) {
				$attrs['data-parent-fit'] = esc_attr( $this->parent_fit );
			}

			// set an src if true in options, since lazyloading this is "data-src"
			if ( ! $this->as_bg && $this->src ) {
				$attrs['data-src'] = esc_attr( $src );
			}

			// the shim attribute for srcset.
			$shim_src = $this->get_shim();

			if ( ! $this->as_bg && $this->use_srcset && ! empty( $this->srcset_sizes ) ) {
				$attrs['srcset'] = esc_attr( $shim_src );
			}

			// the sizes attribute for srcset
			if ( $this->use_srcset && ! empty( $this->srcset_sizes ) ) {
				$sizes_value         = $this->auto_sizes_attr ? 'auto' : $this->srcset_sizes_attr;
				$attrs['data-sizes'] = esc_attr( $sizes_value );
			}

			// generate the srcset attribute if wanted
			if ( $this->use_srcset && ! empty( $this->srcset_sizes ) ) {
				$attribute_name           = $this->as_bg ? 'data-bgset' : 'data-srcset';
				$srcset_urls              = $this->get_srcset_attribute();
				$attrs[ $attribute_name ] = esc_attr( $srcset_urls );
			}

			// setup the shim
			if ( $this->as_bg && ! empty( $shim_src ) ) {
				$attrs['style'] = sprintf( 'background-image:url(\'%s\');', esc_url( $shim_src ) );
			} else {
				$attrs['src'] = esc_url( $shim_src );
			}

		} else {

			// no lazyloading, standard stuffs
			if ( $this->as_bg && ! empty( $src ) ) {
				$attrs['style'] = sprintf( 'background-image:url(\'%s\');', esc_url( $src ) );
			} else {
				$attrs['src'] = $this->src ? esc_url( $src ) : '';
				if ( $this->use_srcset && ! empty( $this->srcset_sizes ) ) {
					$srcset_urls     = $this->get_srcset_attribute();
					$attrs['sizes']  = esc_attr( $this->srcset_sizes_attr );
					$attrs['srcset'] = esc_attr( $srcset_urls );
				}
				if ( $this->use_hw_attr && $this->src ) {
					$attrs['width']  = esc_attr( $src_width / 2 );
					$attrs['height'] = esc_attr( $src_height / 2 );
				}
			}
		}

		return Markup_Utils::concat_attrs( $attrs );
	}


	/**
	 * Returns shim src for lazyloading on request. Auto shim uses size name to lookup png file
	 * in shims directory.
	 *
	 * @return string
	 */
	public function get_shim(): string {
		$shim_dir = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/theme/shims/';
		$src      = $this->shim;

		if ( empty( $this->shim ) ) {
			if ( $this->auto_shim ) {
				$src = $shim_dir . $this->src_size . '.png';
			} else {
				$src = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
			}
		}

		return $src;
	}

	/**
	 * Loops over wp registered sizes and forms a valid srcset string with width and height values
	 *
	 * @return string
	 */
	public function get_srcset_attribute(): string {
		if ( ! $this->img_id ) {
			return '';
		}

		$attribute = [];
		$all_sizes = wp_get_additional_image_sizes();

		foreach ( $this->srcset_sizes as $size ) {
			if ( $size === 'full' ) {
				continue;
			}
			$src = wp_get_attachment_image_src( $this->img_id, $size );
			if ( ! $src ) {
				continue;
			}
			// Don't add nonexistent intermediate sizes to the src_set. It ends up being the full-size image URL.
			$use_size = ( true === $src[3] );
			if ( ! $use_size && isset( $all_sizes[ $size ] ) ) {
				$use_size = $this->image_matches_size( $src, $all_sizes[ $size ] );
			}
			if ( $use_size ) {
				$attribute[] = $this->build_srcset_string( ... $src );
			}
		}

		// If there are no sizes available after all that work, fallback to the original full size image.
		if ( empty( $attribute ) ) {
			$src = wp_get_attachment_image_src( $this->img_id, 'full' );
			if ( $src ) {
				$attribute[] = $this->build_srcset_string( ... $src );
			}
		}

		return implode( ", \n", $attribute );
	}

	private function build_srcset_string( $url, $width, $height ): string {
		return sprintf( '%s %dw %dh', $url, $width, $height );
	}

	/**
	 * Determine if the image meets the dimensions specified by the image size
	 *
	 * @param array $image An image array from wp_get_attachment_image_src()
	 * @param array $size  An image size array from wp_get_additional_image_sizes()
	 *
	 * @return bool
	 */
	private function image_matches_size( array $image, array $size ): bool {
		$image_width  = (int) $image[1];
		$image_height = (int) $image[2];
		$size_width   = (int) $size['width'];
		$size_height  = (int) $size['height'];
		if ( $size['crop'] ) {
			return ( $image_width === $size_width && $image_height === $size_height );
		} else {
			return ( $image_width === $size_width || $image_height === $size_height );
		}
	}

	public function get_html(): string {
		if ( $this->html ) {
			return '';
		}

		return $this->html;
	}
}
