<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Theme\Util;

class Image extends Component {

	protected $path =  __DIR__ . '/image.twig';

	const ATTACHMENT        = 'attachment';
	const IMG_URL           = 'img_url';
	const AS_BG             = 'as_bg';
	const AUTO_SHIM         = 'auto_shim';
	const AUTO_SIZES_ATTR   = 'auto_sizes_attr';
	const ECHO              = 'echo';
	const EXPAND            = 'expand';
	const HTML              = 'html';
	const IMG_CLASSES       = 'img_classes';
	const IMG_ATTRS         = 'img_attrs';
	const IMG_ALT_TEXT      = 'img_alt_text';
	const LINK_URL          = 'link_url';
	const LINK_CLASSES      = 'link_classes';
	const LINK_TARGET       = 'link_target';
	const LINK_TITLE        = 'link_title';
	const LINK_ATTRS        = 'link_attrs';
	const PARENT_FIT        = 'parent_fit';
	const SHIM              = 'shim';
	const SRC               = 'src';
	const SRC_SIZE          = 'src_size';
	const SRCSET_SIZES      = 'srcset_sizes';
	const SRCSET_SIZES_ATTR = 'srcset_sizes_attr';
	const USE_HW_ATTR       = 'use_hw_attr';
	const USE_LAZYLOAD      = 'use_lazyload';
	const USE_SRCSET        = 'use_srcset';
	const WRAPPER_ATTRS     = 'wrapper_attrs';
	const WRAPPER_CLASSES   = 'wrapper_classes';
	const WRAPPER_TAG       = 'wrapper_tag';

	public function option( $option ) {
		if ( isset( $this->options[ $option ] ) ) {
			return $this->options[ $option ];
		}

		return null;
	}

	protected function parse_options( array $options ): array {
		$defaults = [
			self::ATTACHMENT        => null,
			// the WordPress attachment to use - takes precedence over IMG_URL
			self::IMG_URL           => '',
			// the Image URL - generate markup for an image via its URL. Only applicable if ATTACHMENT is empty.
			self::AS_BG             => false,
			// Generates a background image on a `<div>` instead of a traditional `<img>`.
			self::AUTO_SHIM         => true,
			// if true, shim dir as set will be used, src_size will be used as filename, with png as file type.
			self::AUTO_SIZES_ATTR   => false,
			// if lazyloading the lib can auto create sizes attribute.
			self::EXPAND            => '200',
			// the expand attribute is the threshold used by lazysizes. use negative to reveal once in viewport.
			self::HTML              => '',
			// append an html string inside the wrapper. Useful for adding a `<figcaption>` or other markup after the image.
			self::IMG_CLASSES       => [ 'c-image__image' ],
			// pass classes for image tag. if lazyload is true class "lazyload" is auto added.
			self::IMG_ATTRS         => [],
			// additional image attributes.
			self::IMG_ALT_TEXT      => '',
			// pass specific image alternate text. if not included, will default to image title.
			self::LINK_URL          => '',
			// pass a link to wrap the image.
			self::LINK_CLASSES      => [ 'c-image__link' ],
			// pass link classes.
			self::LINK_TARGET       => '',
			// pass a link target.
			self::LINK_TITLE        => '',
			// pass a link title.
			self::LINK_ATTRS        => [],
			// pass additional link attributes.
			self::PARENT_FIT        => 'width',
			// if lazyloading this combines with object fit css and the object fit polyfill.
			self::SHIM              => '',
			// supply a manually specified shim for lazyloading. Will override auto_shim whether true/false.
			self::SRC               => true,
			// set to false to disable the src attribute. this is a fallback for non srcset browsers.
			self::SRC_SIZE          => 'large',
			// this is the main src registered image size.
			self::SRCSET_SIZES      => [],
			// this is registered sizes array for srcset.
			self::SRCSET_SIZES_ATTR => '(min-width: 1260px) 1260px, 100vw',
			// this is the srcset sizes attribute string used if auto is false.
			self::USE_HW_ATTR       => false,
			// this will set the width and height attributes on the img to be half the original for retina/hdpi. Only for not lazyloading and when src exists.
			self::USE_LAZYLOAD      => true,
			// lazyload this game? If `AS_BG` is true, `SRCSET_SIZES` must also be defined.
			self::USE_SRCSET        => true,
			// srcset this game?
			self::WRAPPER_ATTRS     => [],
			// additional wrapper attributes.
			self::WRAPPER_CLASSES   => [ 'c-image' ],
			// pass classes for figure wrapper. If as_bg is set true gets auto class of "lazyload".
			self::WRAPPER_TAG       => 'figure',
			// html tag for the wrapper/background image container.
		];

		return wp_parse_args( $options, $defaults );
	}

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
			'html'    => $this->options[ self::HTML ],
		];

		return $data;
	}

	protected function should_lazy_load(): bool {
		$missing_src = empty( $this->options[ self::ATTACHMENT ] ) && empty( $this->options[ self::IMG_URL ] );

		return $this->options[ self::USE_LAZYLOAD ] && ! $missing_src;
	}

	/**
	 * Get the component's image markup.
	 *
	 * Set to `<img />` if `AS_BG` is false and `<div>` if `AS_BG` is true.
	 *
	 * @return string
	 */
	protected function get_image(): string {
		$classes = $this->options[ self::IMG_CLASSES ];

		if ( $this->should_lazy_load() ) {
			$classes[] = 'lazyload';
		}

		if ( $this->options[ self::AS_BG ] ) {
			// <div classes attrs></div>
			$image = sprintf( '<div %s %s></div>', Util::class_attribute( $classes ), $this->get_attributes() );
		} else {
			// <img classes attrs />
			$image = sprintf( '<img %s %s />', Util::class_attribute( $classes ), $this->get_attributes() );
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
		$tag = $this->options[ self::WRAPPER_TAG ];

		if ( empty( $tag ) ) {
			$tag = $this->options[ self::AS_BG ] ? 'div' : 'figure';
		}

		return [
			'tag'     => esc_attr( $tag ),
			'attrs'   => Util::array_to_attributes( $this->options[ self::WRAPPER_ATTRS ] ),
			'classes' => Util::class_attribute( $this->options[ self::WRAPPER_CLASSES ] ),
		];
	}

	/**
	 * Get the component's link container markup.
	 *
	 * @return array
	 */
	protected function get_link(): array {
		if ( empty( $this->options[ self::LINK_URL ] ) ) {
			return [];
		}

		$attrs = $this->options[ self::LINK_ATTRS ];

		if ( ! empty( $this->options[ self::LINK_TARGET ] ) ) {
			$attrs['target'] = esc_attr( $this->options[ self::LINK_TARGET ] );
		}

		if ( $this->options[ self::LINK_TARGET ] === '_blank' ) {
			$attrs['rel'] = 'noopener';
		}

		return [
			'url'     => esc_url( $this->options[ self::LINK_URL ] ),
			'classes' => Util::class_attribute( $this->options[ self::LINK_CLASSES ] ),
			'attrs'   => Util::array_to_attributes( $attrs ),
		];
	}

	/**
	 * Util to set item attributes for lazyload or not, bg or not.
	 *
	 * @return string
	 */
	private function get_attributes(): string {
		$attrs = $this->options[ self::IMG_ATTRS ];
		/** @var \Tribe\Project\Templates\Models\Image $attachment */
		$attachment = $this->options[ self::ATTACHMENT ];
		$src_width  = '';
		$src_height = '';
		// we'll almost always set src, except if for some reason they wanted to only use srcset
		if ( $attachment && $attachment->has_size( $this->options[ self::SRC_SIZE ] ) ) {
			$resized    = $attachment->get_size( $this->options[ self::SRC_SIZE ] );
			$src        = $resized->src;
			$src_width  = $resized->width;
			$src_height = $resized->height;
		} else {
			$src = $this->options[ self::IMG_URL ];
		}

		// the alt text
		$alt_text = $this->options[ self::IMG_ALT_TEXT ];

		// Check for a specific alt meta value on the image post, otherwise fallback to the image post's title.
		if ( empty( $alt_text ) && $attachment ) {
			$alt_text = $attachment->alt() ?: $attachment->title();
		}

		if ( $this->options[ self::AS_BG ] ) {
			$attrs['role']       = 'img';
			$attrs['aria-label'] = esc_attr( $alt_text );
		} else {
			$attrs['alt'] = esc_attr( $alt_text );
		}

		if ( $this->options[ self::USE_LAZYLOAD ] ) {

			// the expand attribute that controls threshold
			$attrs['data-expand'] = esc_attr( $this->options[ self::EXPAND ] );

			// the parent fit attribute if as_bg is not used.
			if ( ! $this->options[ self::AS_BG ] ) {
				$attrs['data-parent-fit'] = esc_attr( $this->options[ self::PARENT_FIT ] );
			}

			// set an src if true in options, since lazyloading this is "data-src"
			if ( ! $this->options[ self::AS_BG ] && $this->options[ self::SRC ] ) {
				$attrs['data-src'] = esc_attr( $src );
			}

			// the shim attribute for srcset.
			$shim_src = $this->get_shim();

			if ( ! $this->options[ self::AS_BG ] && $this->options[ self::USE_SRCSET ] && ! empty( $this->options[ self::SRCSET_SIZES ] ) ) {
				$attrs['srcset'] = esc_attr( $shim_src );
			}

			// the sizes attribute for srcset
			if ( $this->options[ self::USE_SRCSET ] && ! empty( $this->options[ self::SRCSET_SIZES ] ) ) {
				$sizes_value         = $this->options[ self::AUTO_SIZES_ATTR ] ? 'auto' : $this->options[ self::SRCSET_SIZES_ATTR ];
				$attrs['data-sizes'] = esc_attr( $sizes_value );
			}

			// generate the srcset attribute if wanted
			if ( $this->options[ self::USE_SRCSET ] && ! empty( $this->options[ self::SRCSET_SIZES ] ) ) {
				$attribute_name           = $this->options[ self::AS_BG ] ? 'data-bgset' : 'data-srcset';
				$srcset_urls              = $this->get_srcset_attribute();
				$attrs[ $attribute_name ] = esc_attr( $srcset_urls );
			}

			// setup the shim
			if ( $this->options[ self::AS_BG ] && ! empty( $shim_src ) ) {
				$attrs['style'] = sprintf( 'background-image:url(\'%s\');', esc_url( $shim_src ) );
			} else {
				$attrs['src'] = esc_url( $shim_src );
			}

		} else {

			// no lazyloading, standard stuffs
			if ( $this->options[ self::AS_BG ] && ! empty( $src ) ) {
				$attrs['style'] = sprintf( 'background-image:url(\'%s\');', esc_url( $src ) );
			} else {
				$attrs['src'] = $this->options[ self::SRC ] ? esc_url( $src ) : '';
				if ( $this->options[ self::USE_SRCSET ] && ! empty( $this->options[ self::SRCSET_SIZES ] ) ) {
					$srcset_urls     = $this->get_srcset_attribute();
					$attrs['sizes']  = esc_attr( $this->options[ self::SRCSET_SIZES_ATTR ] );
					$attrs['srcset'] = esc_attr( $srcset_urls );
				}
				if ( $this->options[ self::USE_HW_ATTR ] && $this->options[ self::SRC ] ) {
					$attrs['width']  = esc_attr( $src_width / 2 );
					$attrs['height'] = esc_attr( $src_height / 2 );
				}
			}
		}

		return Util::array_to_attributes( $attrs );
	}


	/**
	 * Returns shim src for lazyloading on request. Auto shim uses size name to lookup png file
	 * in shims directory.
	 *
	 * @return string
	 */
	private function get_shim(): string {
		$shim_dir = trailingslashit( get_stylesheet_directory_uri() ) . 'img/theme/shims/';
		$src      = $this->options[ self::SHIM ];

		if ( empty ( $this->options[ self::SHIM ] ) ) {
			if ( $this->options[ self::AUTO_SHIM ] ) {
				$src = $shim_dir . $this->options[ self::SRC_SIZE ] . '.png';
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
		if ( ! isset( $this->options[ self::ATTACHMENT ] ) ) {
			return '';
		}

		$attribute = [];

		/** @var \Tribe\Project\Templates\Models\Image $attachment */
		$attachment = $this->options[ self::ATTACHMENT ];
		foreach ( $this->options[ self::SRCSET_SIZES ] as $size ) {
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
