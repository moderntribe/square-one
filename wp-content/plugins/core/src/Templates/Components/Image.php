<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Theme\Util;

class Image extends Component {

	const TEMPLATE_NAME = 'components/image.twig';

	const IMG_ID            = 'img_id';
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
			static::IMG_ID             => 0,
			// the Image ID - takes precedence over IMG_URL.
			static::IMG_URL            => '',
			// the Image URL - generate markup for an image via its URL. Only applicable if IMAGE_ID is empty.
			static::AS_BG              => false,
			// Generates a background image on a `<div>` instead of a traditional `<img>`.
			static::AUTO_SHIM          => true,
			// if true, shim dir as set will be used, src_size will be used as filename, with png as file type.
			static::AUTO_SIZES_ATTR    => false,
			// if lazyloading the lib can auto create sizes attribute.
			static::ECHO               => true,
			// whether to echo or return the html.
			static::EXPAND             => '200',
			// the expand attribute is the threshold used by lazysizes. use negative to reveal once in viewport.
			static::HTML               => '',
			// append an html string inside the wrapper. Useful for adding a `<figcaption>` or other markup after the image.
			static::IMG_CLASSES        => [ 'c-image__image' ],
			// pass classes for image tag. if lazyload is true class "lazyload" is auto added.
			static::IMG_ATTRS          => [],
			// additional image attributes.
			static::IMG_ALT_TEXT       => '',
			// pass specific image alternate text. if not included, will default to image title.
			static::LINK_URL           => '',
			// pass a link to wrap the image.
			static::LINK_CLASSES       => [ 'c-image__link' ],
			// pass link classes.
			static::LINK_TARGET        => '',
			// pass a link target.
			static::LINK_TITLE         => '',
			// pass a link title.
			static::LINK_ATTRS         => [],
			// pass additional link attributes.
			static::PARENT_FIT         => 'width',
			// if lazyloading this combines with object fit css and the object fit polyfill.
			static::SHIM               => '',
			// supply a manually specified shim for lazyloading. Will override auto_shim whether true/false.
			static::SRC                => true,
			// set to false to disable the src attribute. this is a fallback for non srcset browsers.
			static::SRC_SIZE           => 'large',
			// this is the main src registered image size.
			static::SRCSET_SIZES       => [],
			// this is registered sizes array for srcset.
			static::SRCSET_SIZES_ATTR  => '(min-width: 1260px) 1260px, 100vw',
			// this is the srcset sizes attribute string used if auto is false.
			static::USE_HW_ATTR        => false,
			// this will set the width and height attributes on the img to be half the original for retina/hdpi. Only for not lazyloading and when src exists.
			static::USE_LAZYLOAD       => true,
			// lazyload this game? If `AS_BG` is true, `SRCSET_SIZES` must also be defined.
			static::USE_SRCSET         => true,
			// srcset this game?
			static::WRAPPER_ATTRS      => [],
			// additional wrapper attributes.
			static::WRAPPER_CLASSES    => [ 'c-image' ],
			// pass classes for figure wrapper. If as_bg is set true gets auto class of "lazyload".
			static::WRAPPER_TAG        => 'figure',
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
			'html'    => $this->options[ static::HTML ],
		];

		return $data;
	}

	protected function should_lazy_load(): bool {
		$missing_src = empty( $this->options[ static::IMG_ID ] ) && empty( $this->options[ static::IMG_URL ] );
		return $this->options[ static::USE_LAZYLOAD ] && ! $missing_src;
	}

	/**
	 * Get the component's image markup.
	 *
	 * Set to `<img />` if `AS_BG` is false and `<div>` if `AS_BG` is true.
	 *
	 * @return string
	 */
	protected function get_image(): string {
		$classes = $this->options[ static::IMG_CLASSES ];

		if ( $this->should_lazy_load() ) {
			$classes[] = 'lazyload';
		}

		if ( $this->options[ static::AS_BG ] ) {
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
		$tag = $this->options[ static::WRAPPER_TAG ];

		if ( empty( $tag ) ) {
			$tag = $this->options[ static::AS_BG ] ? 'div' : 'figure';
		}

		return [
			'tag'     => esc_attr( $tag ),
			'attrs'   => Util::array_to_attributes( $this->options[ static::WRAPPER_ATTRS ] ),
			'classes' => Util::class_attribute( $this->options[ static::WRAPPER_CLASSES ] ),
		];
	}

	/**
	 * Get the component's link container markup.
	 *
	 * @return array
	 */
	protected function get_link(): array {
		if ( empty( $this->options[ static::LINK_URL ] ) ) {
			return [];
		}

		$attrs = $this->options[ static::LINK_ATTRS ];

		if ( ! empty( $this->options[ static::LINK_TARGET ] ) ) {
			$attrs[ 'target' ] = esc_attr( $this->options[ static::LINK_TARGET ] );
		}

		if ( $this->options[ static::LINK_TARGET ] === '_blank' ) {
			$attrs[ 'rel' ] = 'noopener';
		}

		return [
			'url'     => esc_url( $this->options[ static::LINK_URL ] ),
			'classes' => Util::class_attribute( $this->options[ static::LINK_CLASSES ] ),
			'attrs'   => Util::array_to_attributes( $attrs )
		];
	}

	/**
	 * Util to set item attributes for lazyload or not, bg or not.
	 *
	 * @return string
	 */
	private function get_attributes(): string {
		$has_image_id = $this->options[ static::IMG_ID ] !== 0;
		$attrs        = $this->options[ static::IMG_ATTRS ];
		$src          = '';
		$src_width    = '';
		$src_height   = '';

		// we'll almost always set src, except if for some reason they wanted to only use srcset
		if ( $this->options[ static::SRC ] ) {
			if ( $has_image_id ) { // image_id takes precedence
				$src        = wp_get_attachment_image_src( $this->options[ static::IMG_ID ], $this->options[ static::SRC_SIZE ] );
				$src_width  = $src[ 1 ];
				$src_height = $src[ 2 ];
				$src        = $src[ 0 ];
			} else { // using IMG_URL
				$src = $this->options[ static::IMG_URL ];
			}
		}

		// the alt text
		$alt_text = $this->options[ static::IMG_ALT_TEXT ];

		// Check for a specific alt meta value on the image post, otherwise fallback to the image post's title.
		if ( empty( $alt_text ) && $has_image_id ) {
			$alt_meta_value = get_post_meta( $this->options[ static::IMG_ID ], '_wp_attachment_image_alt', true );
			$alt_text       = ! empty( $alt_meta_value ) ? $alt_meta_value : get_the_title( $this->options[ static::IMG_ID ] );
		}

		if ( $this->options[ static::AS_BG ] ) {
			$attrs[ 'role' ]      = 'img';
			$attrs[ 'aria-label'] = esc_attr( $alt_text );
		} else {
			$attrs[ 'alt' ] = esc_attr( $alt_text );
		}

		if ( $this->options[ static::USE_LAZYLOAD ] ) {

			// the expand attribute that controls threshold
			$attrs[ 'data-expand' ] = esc_attr( $this->options[ static::EXPAND ] );

			// the parent fit attribute if as_bg is not used.
			if ( ! $this->options[ static::AS_BG ] ) {
				$attrs[ 'data-parent-fit' ] = esc_attr( $this->options[ static::PARENT_FIT ] );
			}

			// set an src if true in options, since lazyloading this is "data-src"
			if ( ! $this->options[ static::AS_BG ] && $this->options[ static::SRC ] ) {
				$attrs[ 'data-src' ] = esc_attr( $src );
			}

			// the shim attribute for srcset.
			$shim_src = $this->get_shim();

			if ( ! $this->options[ static::AS_BG ] && $this->options[ static::USE_SRCSET ] && ! empty( $this->options[ static::SRCSET_SIZES ] ) ) {
				$attrs[ 'srcset' ] = esc_attr( $shim_src );
			}

			// the sizes attribute for srcset
			if ( $this->options[ static::USE_SRCSET ] && ! empty( $this->options[ static::SRCSET_SIZES ] ) ) {
				$sizes_value = $this->options[ static::AUTO_SIZES_ATTR ] ? 'auto' : $this->options[ static::SRCSET_SIZES_ATTR ];
				$attrs[ 'data-sizes' ] = esc_attr( $sizes_value );
			}

			// generate the srcset attribute if wanted
			if ( $this->options[ static::USE_SRCSET ] && ! empty( $this->options[ static::SRCSET_SIZES ] ) ) {
				$attribute_name = $this->options[ static::AS_BG ] ? 'data-bgset' : 'data-srcset';
				$srcset_urls    = $this->get_srcset_attribute();
				$attrs[ $attribute_name ] = esc_attr( $srcset_urls );
			}

			// setup the shim
			if ( $this->options[ static::AS_BG ] && ! empty( $shim_src ) ) {
				$attrs[ 'style' ] = sprintf( 'background-image:url(\'%s\');', esc_url( $shim_src ) );
			} else {
				$attrs[ 'src' ] = esc_url( $shim_src );
			}

		} else {

			// no lazyloading, standard stuffs
			if ( $this->options[ static::AS_BG ] && ! empty( $src ) ) {
				$attrs[ 'style' ] = sprintf( 'background-image:url(\'%s\');', esc_url( $src ) );
			} else {
				$attrs[ 'src' ] = $this->options[ static::SRC ] ? esc_url( $src ) : '';
				if ( $this->options[ static::USE_SRCSET ] && ! empty( $this->options[ static::SRCSET_SIZES ] ) ) {
					$srcset_urls = $this->get_srcset_attribute();
					$attrs[ 'sizes' ]  = esc_attr( $this->options[ static::SRCSET_SIZES_ATTR ] );
					$attrs[ 'srcset' ] = esc_attr( $srcset_urls );
				}
				if ( $this->options[ static::USE_HW_ATTR ] && $this->options[ static::SRC ] ) {
					$attrs[ 'width' ]  = esc_attr( $src_width / 2 );
					$attrs[ 'height' ] = esc_attr( $src_height / 2 );
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
		$src      = $this->options[ static::SHIM ];

		if ( empty ( $this->options[ static::SHIM ] ) ) {
			if ( $this->options[ static::AUTO_SHIM ] ) {
				$src = $shim_dir . $this->options[ static::SRC_SIZE ] . '.png';
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
		$all_sizes = wp_get_additional_image_sizes();
		$attribute = [];

		if ( $this->options[ static::IMG_ID ] === 0 ) {
			return '';
		}

		foreach ( $this->options[ static::SRCSET_SIZES ] as $size ) {
			$src = wp_get_attachment_image_src( $this->options[ static::IMG_ID ], $size );
			// Don't add nonexistent intermediate sizes to the src_set. It ends up being the full-size image URL.
			$use_size = ( 'full' !== $size && true === $src[ 3 ] );
			if ( ! $use_size && isset( $all_sizes[ $size ] ) ) {
				$use_size = $this->image_matches_size( $src, $all_sizes[ $size ] );
			}
			if ( $use_size ) {
				$attribute[] = sprintf( '%s %dw %dh', $src[ 0 ], $src[ 1 ], $src[ 2 ] );
			}
		}

		// If there are no sizes available after all that work, fallback to the original full size image.
		if ( empty( $attribute ) ) {
			$src         = wp_get_attachment_image_src( $this->options[ static::IMG_ID ], 'full' );
			$attribute[] = sprintf( '%s %dw %dh', $src[ 0 ], $src[ 1 ], $src[ 2 ] );
		}

		return implode( ", \n", $attribute );
	}

	/**
	 * Determine if the image meets the dimensions specified by the image size
	 *
	 * @param array $image An image array from wp_get_attachment_image_src()
	 * @param array $size An image size array from wp_get_additional_image_sizes()
	 *
	 * @return bool
	 */
	private function image_matches_size( $image, $size ): bool {
		if ( $size[ 'crop' ] ) {
			return ( $image[ 1 ] == $size[ 'width' ] && $image[ 2 ] == $size[ 'height' ] );
		} else {
			return ( $image[ 1 ] == $size[ 'width' ] || $image[ 2 ] == $size[ 'height' ] );
		}
	}
}
