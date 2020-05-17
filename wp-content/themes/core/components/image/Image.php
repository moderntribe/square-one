<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Models\Image as Image_Model;

/**
 * Class Image
 *
 * @property Image_Model $attachment
 * @property string      $img_url
 * @property bool        $as_bg
 * @property bool        $auto_shim
 * @property bool        $auto_sizes_attr
 * @property int         $expand
 * @property string      $html
 * @property string[]    $img_classes
 * @property string[]    $img_attrs
 * @property string      $img_alt_text
 * @property string      $link_url
 * @property string[]    $link_classes
 * @property string      $link_target
 * @property string      $link_title
 * @property string []   $link_attrs
 * @property string      $parent_fit
 * @property string      $shim
 * @property bool        $src
 * @property string      $src_size
 * @property string[]    $srcset_sizes
 * @property string      $srcset_sizes_attr
 * @property bool        $use_hw_attr
 * @property bool        $use_lazyload
 * @property bool        $use_srcset
 * @property string[]    $wrapper_attrs
 * @property string[]    $wrapper_classes
 * @property string      $wrapper_tag
 */
class Image extends Context {
	public const ATTACHMENT        = 'attachment';
	public const IMG_URL           = 'img_url';
	public const AS_BG             = 'as_bg';
	public const AUTO_SHIM         = 'auto_shim';
	public const AUTO_SIZES_ATTR   = 'auto_sizes_attr';
	public const ECHO              = 'echo';
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
	public const WRAPPER_ATTRS     = 'wrapper_attrs';
	public const WRAPPER_CLASSES   = 'wrapper_classes';
	public const WRAPPER_TAG       = 'wrapper_tag';

	protected $path = __DIR__ . '/image.twig';

	protected $properties = [

		// the WordPress attachment to use - takes precedence over IMG_URL
		self::ATTACHMENT        => [
			self::DEFAULT => null,
		],
		// the Image URL - generate markup for an image via its URL. Only applicable if ATTACHMENT is empty
		self::IMG_URL           => [
			self::DEFAULT => '',
		],
		// Generates a background image on a `<div>` instead of a traditional `<img>`
		self::AS_BG             => [
			self::DEFAULT => false,
		],
		// if true, shim dir as set will be used, src_size will be used as filename, with png as file type
		self::AUTO_SHIM         => [
			self::DEFAULT => true,
		],
		// if lazyloading the lib can auto create sizes attribute
		self::AUTO_SIZES_ATTR   => [
			self::DEFAULT => false,
		],
		// the expand attribute is the threshold used by lazysizes. use negative to reveal once in viewport
		self::EXPAND            => [
			self::DEFAULT => 200,
		],
		// append an html string inside the wrapper. Useful for adding a `<figcaption>` or other markup after the image
		self::HTML              => [
			self::DEFAULT => '',
		],
		// pass classes for image tag. if lazyload is true class "lazyload" is auto added
		self::IMG_CLASSES       => [
			self::DEFAULT => [ 'c-image__image' ],
		],
		// additional image attributes
		self::IMG_ATTRS         => [
			self::DEFAULT => [],
		],
		// pass specific image alternate text. if not included, will default to image title
		self::IMG_ALT_TEXT      => [
			self::DEFAULT => '',
		],
		// pass a link to wrap the image
		self::LINK_URL          => [
			self::DEFAULT => '',
		],
		// pass link classes
		self::LINK_CLASSES      => [
			self::DEFAULT => [ 'c-image__link' ],
		],
		// pass a link target
		self::LINK_TARGET       => [
			self::DEFAULT => '',
		],
		// pass a link title
		self::LINK_TITLE        => [
			self::DEFAULT => '',
		],
		// pass additional link attributes
		self::LINK_ATTRS        => [
			self::DEFAULT => [],
		],
		// if lazyloading this combines with object fit css and the object fit polyfill
		self::PARENT_FIT        => [
			self::DEFAULT => 'width',
		],
		// supply a manually specified shim for lazyloading. Will override auto_shim whether true/false
		self::SHIM              => [
			self::DEFAULT => '',
		],
		// set to false to disable the src attribute. this is a fallback for non srcset browsers
		self::SRC               => [
			self::DEFAULT => true,
		],
		// this is the main src registered image size
		self::SRC_SIZE          => [
			self::DEFAULT => 'large',
		],
		// this is registered sizes array for srcset
		self::SRCSET_SIZES      => [
			self::DEFAULT => [],
		],
		// this is the srcset sizes attribute string used if auto is false
		self::SRCSET_SIZES_ATTR => [
			self::DEFAULT => '(min-width: 1260px) 1260px, 100vw',
		],
		// this will set the width and height attributes on the img to be half the original for retina/hdpi. Only for not lazyloading and when src exists
		self::USE_HW_ATTR       => [
			self::DEFAULT => false,
		],
		// lazyload this game? If `AS_BG` is true, `SRCSET_SIZES` must also be defined
		self::USE_LAZYLOAD      => [
			self::DEFAULT => true,
		],
		// srcset this game?
		self::USE_SRCSET        => [
			self::DEFAULT => true,
		],
		self::WRAPPER_ATTRS     => [
			self::DEFAULT => [],
		],
		// pass classes for figure wrapper. If as_bg is set true gets auto class of "lazyload"
		self::WRAPPER_CLASSES   => [
			self::DEFAULT => [ 'c-image' ],
		],
		// html tag for the wrapper/background image container
		self::WRAPPER_TAG       => [
			self::DEFAULT => 'figure',
		],
	];

	/**
	 * Forms the html for the image component
	 *
	 * @return array
	 */
	public function get_data(): array {
		$data = [
			'wrapper' => $this->get_wrapper(),
			'image'   => $this->get_image(),
			'link'    => $this->get_link(),
			'html'    => $this->html,
		];

		return $data;
	}

	protected function should_lazy_load(): bool {
		$missing_src = empty( $this->attachment ) && empty( $this->img_url );

		return $this->use_lazyload && ! $missing_src;
	}

	/**
	 * Get the component's image markup.
	 *
	 * Set to `<img />` if `AS_BG` is false and `<div>` if `AS_BG` is true.
	 *
	 * @return string
	 */
	protected function get_image(): string {
		$classes = $this->img_classes;

		if ( $this->should_lazy_load() ) {
			$classes[] = 'lazyload';
		}

		if ( $this->as_bg ) {
			// <div classes attrs></div>
			$image = sprintf( '<div %s %s></div>', Markup_Utils::class_attribute( $classes ), $this->get_attributes() );
		} else {
			// <img classes attrs />
			$image = sprintf( '<img %s %s />', Markup_Utils::class_attribute( $classes ), $this->get_attributes() );
		}

		return $image;
	}

	/**
	 * Get the component's wrapper element markup.
	 *
	 * Defaults to `<figure>` if `AS_BG` is false and `<div>` if `AS_BG` is true.
	 *
	 * @return array
	 */
	protected function get_wrapper(): array {
		$tag = $this->wrapper_tag;

		if ( empty( $tag ) ) {
			$tag = $this->as_bg ? 'div' : 'figure';
		}

		return [
			'tag'     => esc_attr( $tag ),
			'attrs'   => Markup_Utils::concat_attrs( $this->wrapper_attrs ),
			'classes' => Markup_Utils::class_attribute( $this->wrapper_classes ),
		];
	}

	/**
	 * Get the component's link container markup.
	 *
	 * @return array
	 */
	protected function get_link(): array {
		if ( empty( $this->link_url ) ) {
			return [];
		}

		$attrs = $this->link_attrs;

		if ( ! empty( $this->link_target ) ) {
			$attrs['target'] = esc_attr( $this->link_target );
		}

		if ( $this->link_target === '_blank' ) {
			$attrs['rel'] = 'noopener';
		}

		return [
			'url'     => esc_url( $this->link_url ),
			'classes' => Markup_Utils::class_attribute( $this->link_classes ),
			'attrs'   => Markup_Utils::concat_attrs( $attrs ),
		];
	}

	/**
	 * Util to set item attributes for lazyload or not, bg or not.
	 *
	 * @return string
	 */
	private function get_attributes(): string {
		$attrs = $this->img_attrs;
		/** @var Image_Model $attachment */
		$attachment = $this->attachment;
		$src_width  = '';
		$src_height = '';
		// we'll almost always set src, except if for some reason they wanted to only use srcset
		if ( $attachment && $attachment->has_size( $this->src_size ) ) {
			$resized    = $attachment->get_size( $this->src_size );
			$src        = $resized->src;
			$src_width  = $resized->width;
			$src_height = $resized->height;
		} else {
			$src = $this->img_url;
		}

		// the alt text
		$alt_text = $this->img_alt_text;

		// Check for a specific alt meta value on the image post, otherwise fallback to the image post's title.
		if ( empty( $alt_text ) && $attachment ) {
			$alt_text = $attachment->alt() ?: $attachment->title();
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
	private function get_shim(): string {
		$shim_dir = trailingslashit( get_stylesheet_directory_uri() ) . 'img/theme/shims/';
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
	private function get_srcset_attribute(): string {
		if ( ! isset( $this->attachment ) ) {
			return '';
		}

		$attribute = [];

		/** @var Image_Model $attachment */
		$attachment = $this->attachment;
		foreach ( $this->srcset_sizes as $size ) {
			if ( $size === 'full' || ! $attachment->has_size( $size ) ) {
				continue;
			}
			$resized = $attachment->get_size( $size );
			if ( ! $resized->is_intermediate || ! $resized->is_match ) {
				continue;
			}
			$attribute[] = sprintf( '%s %dw %dh', $resized->src, $resized->width, $resized->height );
		}

		// If there are no sizes available after all that work, fallback to the original full size image.
		if ( empty( $attribute ) && $attachment->has_size( 'full' ) ) {
			$full        = $attachment->get_size( 'full' );
			$attribute[] = sprintf( '%s %dw %dh', $full->src, $full->width, $full->height );
		}

		return implode( ", \n", $attribute );
	}
}
