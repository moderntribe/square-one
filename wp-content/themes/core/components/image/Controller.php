<?php

namespace Tribe\Project\Templates\Components\image;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Image as Image_Model;

class Controller extends Abstract_Controller {
	/**
	 * WordPress attachment to use - takes precedence over IMG_URL
	 * @var object
	 */
	public $attachment;
	/**
	 * Image URL - generate markup for an image via its URL.
	 * Only applicable if ATTACHMENT is empty.
	 * @var string
	 */
	private $img_url;
	/**
	 * Generates a background image on a `<div>` instead of a
	 * traditional `<img>`.
	 * @var bool
	 */
	private $as_bg;
	/**
	 * If true, shim dir as set will be used, src_size will be
	 * used as filename, with png as file type.
	 * @var bool
	 */
	private $auto_shim;
	/**
	 * If lazyloading the lib can auto create sizes attribute
	 * @var bool
	 */
	private $auto_sizes_attr;
	/**
	 * Expand attribute is the threshold used by lazysizes.
	 * Use negative to reveal once in viewport.
	 * @var int
	 */
	private $expand;
	/**
	 * Append an html string inside the wrapper.
	 * Useful for adding a `<figcaption>` or other markup
	 * after the image.
	 * @var string
	 */
	public $html;
	/**
	 * Pass classes for image tag. if lazyload is true class
	 * "lazyload" is auto added.
	 * @var string[]
	 */
	private $img_classes;
	/**
	 * Additional image attributes
	 * @var string[]
	 */
	private $img_attrs;
	/**
	 * Pass specific image alternate text.
	 * If not included, will default to image alt text and then title.
	 * @var string
	 */
	private $img_alt_text;
	/**
	 * Pass a link to wrap the image
	 * @var string
	 */
	public $link_url;
	/**
	 * Pass link classes
	 * @var string[]
	 */
	private $link_classes;
	/**
	 * Pass a link target
	 * @var string
	 */
	private $link_target;
	/**
	 * Pass a link title
	 * @var string
	 */
	private $link_title;
	/**
	 * Pass additional link attributes
	 * @var string[]
	 */
	private $link_attrs;
	/**
	 * If lazyloading this combines with object fit css
	 * and the object fit polyfill
	 * @var string
	 */
	private $parent_fit;
	/**
	 * Supply a manually specified shim for lazyloading.
	 * Will override auto_shim whether true/false.
	 * @var string
	 */
	private $shim;
	/**
	 * Set to false to disable the src attribute.
	 * This is a fallback for non srcset browsers.
	 * @var bool
	 */
	private $src;
	/**
	 * This is the main src registered image size
	 * @var string
	 */
	private $src_size;
	/**
	 * This is registered sizes array for srcset
	 * @var array
	 */
	private $srcset_sizes;
	/**
	 * This is the srcset sizes attribute string used if auto is false
	 * @var string
	 */
	private $srcset_sizes_attr;
	/**
	 * This will set the width and height attributes on the img to be
	 * half the original for retina/hdpi. Only for not lazyloading
	 * and when src exists.
	 * @var bool
	 */
	private $use_hw_attr;
	/**
	 * Lazyload this game? If `AS_BG` is true, `SRCSET_SIZES`
	 * must also be defined.
	 * @var bool
	 */
	private $use_lazyload;
	/**
	 * Srcset this game?
	 * @var bool
	 */
	private $use_srcset;
	/**
	 * @var string[]
	 */
	private $attrs;
	/**
	 * Pass classes for figure wrapper. If as_bg is set true
	 * gets auto class of "lazyload".
	 * @var string[]
	 */
	private $classes;
	/**
	 * Html tag for the wrapper/background image container
	 * @var string
	 */
	private $wrapper_tag;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			$args[$key] = array_merge( $args[$key], $value );
		}

		$this->attachment        = $args['attachment'];
		$this->attrs             = (array) $args['attrs'];
		$this->classes           = (array) $args['classes'];
		$this->wrapper_tag       = $args['wrapper_tag'];
		$this->img_url           = $args['img_url'];
		$this->as_bg             = $args['as_bg'];
		$this->auto_shim         = $args['auto_shim'];
		$this->auto_sizes_attr   = $args['auto_sizes_attr'];
		$this->expand            = $args['expand'];
		$this->html              = $args['html'];
		$this->img_classes       = (array) $args['img_classes'];
		$this->img_attrs         = (array) $args['img_attrs'];
		$this->img_alt_text      = $args['img_alt_text'];
		$this->link_url          = $args['link_url'];
		$this->link_classes      = (array) $args['link_classes'];
		$this->link_target       = $args['link_target'];
		$this->link_title        = $args['link_title'];
		$this->link_attrs        = (array) $args['link_attrs'];
		$this->parent_fit        = $args['parent_fit'];
		$this->shim              = $args['shim'];
		$this->src               = $args['src'];
		$this->src_size          = $args['src_size'];
		$this->srcset_sizes      = (array) $args['srcset_sizes'];
		$this->srcset_sizes_attr = $args['srcset_sizes_attr'];
		$this->use_hw_attr       = $args['use_hw_attr'];
		$this->use_lazyload      = $args['use_lazyload'];
		$this->use_srcset        = $args['use_srcset'];
	}

	protected function defaults(): array {
		return [
			'attachment'        => null,
			'attrs'             => [],
			'classes'           => [],
			'wrapper_tag'       => 'figure',
			'img_url'           => '',
			'as_bg'             => false,
			'auto_shim'         => true,
			'auto_sizes_attr'   => false,
			'expand'            => 200,
			'html'              => '',
			'img_classes'       => [],
			'img_attrs'         => [],
			'img_alt_text'      => '',
			'link_url'          => '',
			'link_classes'      => [],
			'link_target'       => '',
			'link_title'        => '',
			'link_attrs'        => [],
			'parent_fit'        => 'width',
			'shim'              => '',
			'src'               => true,
			'src_size'          => 'large',
			'srcset_sizes'      => [],
			'srcset_sizes_attr' => '(min-width: 1260px) 1260px, 100vw',
			'use_hw_attr'       => false,
			'use_lazyload'      => true,
			'use_srcset'        => true,
		];
	}

	protected function required(): array {
		return [
			'classes'      => [ 'c-image' ],
			'img_classes'  => [ 'c-image__image' ],
			'link_classes' => [ 'c-image__link' ],
		];
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

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
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
}
