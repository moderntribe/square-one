<?php

namespace Tribe\Project\Templates\Components\image;

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
class Controller {
	public $attachment;
	private $img_url;
	private $as_bg;
	private $auto_shim;
	private $auto_sizes_attr;
	private $expand;
	public $html;
	private $img_classes;
	private $img_attrs;
	private $img_alt_text;
	public $link_url;
	private $link_classes;
	private $link_target;
	private $link_title;
	private $link_attrs;
	private $parent_fit;
	private $shim;
	private $src;
	private $src_size;
	private $srcset_sizes;
	private $srcset_sizes_attr;
	private $use_hw_attr;
	private $use_lazyload;
	private $use_srcset;
	private $wrapper_attrs;
	private $wrapper_classes;
	private $wrapper_tag;

	public function __construct( array $args = [] ) {
		// the WordPress attachment to use - takes precedence over IMG_URL
		$this->attachment = $args['attachment'] ?? null;
		// the Image URL - generate markup for an image via its URL. Only applicable if ATTACHMENT is empty
		$this->img_url = $args['img_url'] ?? '';
		// Generates a background image on a `<div>` instead of a traditional `<img>`
		$this->as_bg = $args['as_bg'] ?? false;
		// if true, shim dir as set will be used, src_size will be used as filename, with png as file type
		$this->auto_shim = $args['auto_shim'] ?? true;
		// if lazyloading the lib can auto create sizes attribute
		$this->auto_sizes_attr = $args['auto_sizes_attr'] ?? false;
		// the expand attribute is the threshold used by lazysizes. use negative to reveal once in viewport
		$this->expand = $args['expand'] ?? 200;
		// append an html string inside the wrapper. Useful for adding a `<figcaption>` or other markup after the image
		$this->html = $args['html'] ?? '';
		// pass classes for image tag. if lazyload is true class "lazyload" is auto added
		$this->img_classes = (array) ( $args['img_classes'] ?? [ 'c-image__image' ] );
		// additional image attributes
		$this->img_attrs = (array) ( $args['img_attrs'] ?? [] );
		// pass specific image alternate text. if not included, will default to image title
		$this->img_alt_text = $args['img_alt_text'] ?? '';
		// pass a link to wrap the image
		$this->link_url = $args['link_url'] ?? '';
		// pass link classes
		$this->link_classes = (array) ( $args['link_classes'] ?? [ 'c-image__link' ] );
		// pass a link target
		$this->link_target = $args['link_target'] ?? '';
		// pass a link title
		$this->link_title = $args['link_title'] ?? '';
		// pass additional link attributes
		$this->link_attrs = (array) ( $args['link_attrs'] ?? [] );
		// if lazyloading this combines with object fit css and the object fit polyfill
		$this->parent_fit = $args['parent_fit'] ?? 'width';
		// supply a manually specified shim for lazyloading. Will override auto_shim whether true/false
		$this->shim = $args['shim'] ?? '';
		// set to false to disable the src attribute. this is a fallback for non srcset browsers
		$this->src = $args['src'] ?? true;
		// this is the main src registered image size
		$this->src_size = $args['src_size'] ?? 'large';
		// this is registered sizes array for srcset
		$this->srcset_sizes = (array) ( $args['srcset_sizes'] ?? [] );
		// this is the srcset sizes attribute string used if auto is false
		$this->srcset_sizes_attr = '(min-width: 1260px) 1260px, 100vw';
		// this will set the width and height attributes on the img to be half the original for retina/hdpi. Only for not lazyloading and when src exists
		$this->use_hw_attr = $args['use_hw_attr'] ?? false;
		// lazyload this game? If `AS_BG` is true, `SRCSET_SIZES` must also be defined
		$this->use_lazyload = $args['use_lazyload'] ?? true;
		// srcset this game?
		$this->use_srcset = $args['use_srcset'] ?? true;
		$this->wrapper_attrs = (array) ( $args['wrapper_attrs'] ?? [] );
		// pass classes for figure wrapper. If as_bg is set true gets auto class of "lazyload"
		$this->wrapper_classes = (array) ( $args['classes'] ?? [ 'c-image' ] );
		// html tag for the wrapper/background image container
		$this->wrapper_tag = $args['wrapper_tag'] ?? 'figure';
	}

	public function should_lazy_load(): bool {
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
	public function get_image(): string {
		$classes = $this->img_classes ?? [];

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
	 * Get the component's wrapper element markup tag.
	 *
	 * Defaults to `<figure>` if `AS_BG` is false and `<div>` if `AS_BG` is true.
	 *
	 * @return string
	 */
	public function wrapper_tag(): string {
		$tag = $this->wrapper_tag;

		if ( empty( $tag ) ) {
			$tag = $this->as_bg ? 'div' : 'figure';
		}

		return esc_attr( $tag );
	}

	public function wrapper_classes(): string {
		return Markup_Utils::class_attribute( $this->wrapper_classes );
	}

	public function wrapper_attributes(): string {
		return Markup_Utils::concat_attrs( $this->wrapper_attrs );
	}

	public function link_classes(): string {
		return Markup_Utils::class_attribute( $this->link_classes );
	}

	public function link_attributes(): string {
		$attrs = $this->link_attrs;

		if ( ! empty( $this->link_target ) ) {
			$attrs['target'] = esc_attr( $this->link_target );
		}

		if ( $this->link_target === '_blank' ) {
			$attrs['rel'] = 'noopener';
		}

		return Markup_Utils::concat_attrs( $attrs );
	}

	/**
	 * Util to set item attributes for lazyload or not, bg or not.
	 *
	 * @return string
	 */
	public function get_attributes(): string {
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

	public static function factory( array $args = [] ): self {
		return new self( $args );
	}
}
