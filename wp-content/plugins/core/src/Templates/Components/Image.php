<?php


namespace Tribe\Project\Templates\Components;

class Image extends Component {

	const TEMPLATE_NAME = 'components/image.twig';

	const IMG_ID             = 'img_id';
	const IMG_URL            = 'img_url';
	const AS_BG              = 'as_bg';
	const AUTO_SHIM          = 'auto_shim';
	const AUTO_SIZES_ATTR    = 'auto_sizes_attr';
	const COMPONENT_CLASS    = 'component_class';
	const ECHO               = 'echo';
	const EXPAND             = 'expand';
	const HTML               = 'html';
	const IMG_CLASS          = 'img_class';
	const IMG_ATTR           = 'img_attr';
	const IMG_ALT_TEXT       = 'img_alt_text';
	const LINK               = 'link';
	const LINK_CLASS         = 'link_class';
	const LINK_TARGET        = 'link_target';
	const LINK_TITLE         = 'link_title';
	const PARENT_FIT         = 'parent_fit';
	const SHIM               = 'shim';
	const SRC                = 'src';
	const SRC_SIZE           = 'src_size';
	const SRCSET_SIZES       = 'srcset_sizes';
	const SRCSET_SIZES__ATTR = 'srcset_sizes_attr';
	const USE_HW_ATTR        = 'use_h&w_attr';
	const USE_LAZYLOAD       = 'use_lazyload';
	const USE_SRCSET         = 'use_srcset';
	const USE_WRAPPER        = 'use_wrapper';
	const WRAPPER_ATTR       = 'wrapper_attr';
	const WRAPPER_CLASS      = 'wrapper_class';
	const WRAPPER_TAG        = 'wrapper_tag';

	public function option( $option ) {
		if ( isset( $this->options[ $option ] ) ) {
			return $this->options[ $option ];
		}

		return null;
	}

	protected function parse_options( array $options ): array {
		$defaults = [
			static::IMG_ID             => 0,
			// the Image ID - takes precedence over IMG_URL
			static::IMG_URL            => '',
			// the Image URL - use as an image URL as a fallback if no IMG_ID exists
			static::AS_BG              => false,
			// us this as background on wrapper?
			static::AUTO_SHIM          => true,
			// if true, shim dir as set will be used, src_size will be used as filename, with png as filetype
			static::AUTO_SIZES_ATTR    => false,
			// pass a specific class to use for the component wrapper
			static::COMPONENT_CLASS    => '',
			// if lazyloading the lib can auto create sizes attribute.
			static::ECHO               => true,
			// whether to echo or return the html
			static::EXPAND             => '200',
			// the expand attribute is the threshold used by lazysizes. use negative to reveal once in viewport.
			static::HTML               => '',
			// append an html string in the wrapper
			static::IMG_CLASS          => '',
			// pass classes for image tag. if lazyload is true class "lazyload" is auto added
			static::IMG_ATTR           => '',
			// additional image attributes
			static::IMG_ALT_TEXT       => '',
			// pass specific image alternate text. if not included, will default to image title
			static::LINK               => '',
			// pass a link to wrap the image
			static::LINK_CLASS         => '',
			// pass link classes
			static::LINK_TARGET        => '_self',
			// pass a link target
			static::LINK_TITLE         => '',
			// pass a link title
			static::PARENT_FIT         => 'width',
			// if lazyloading this combines with object fit css and the object fit polyfill
			static::SHIM               => '',
			// supply a manually specified shim for lazyloading. Will override auto_shim whether true/false.
			static::SRC                => true,
			// set to false to disable the src attribute. this is a fallback for non srcset browsers
			static::SRC_SIZE           => 'large',
			// this is the main src registered image size
			static::SRCSET_SIZES       => [],
			// this is registered sizes array for srcset.
			static::SRCSET_SIZES__ATTR => '(min-width: 1260px) 1260px, 100vw',
			// this is the srcset sizes attribute string used if auto is false.
			static::USE_HW_ATTR        => false,
			// this will set the width and height attributes on the img to be half the origal for retina/hdpi. Only for not lazyloading and when src exists.
			static::USE_LAZYLOAD       => true,
			// lazyload this game?
			static::USE_SRCSET         => true,
			// srcset this game?
			static::USE_WRAPPER        => true,
			// use the wrapper if image
			static::WRAPPER_ATTR       => '',
			// additional wrapper attributes
			static::WRAPPER_CLASS      => 'tribe-image',
			// pass classes for figure wrapper. If as_bg is set true gets auto class of "lazyload"
			static::WRAPPER_TAG        => '',
			// html tag for the wrapper/background image container
		];

		return wp_parse_args( $options, $defaults );
	}

	/**
	 * Forms the html for the image
	 *
	 * @return array
	 */
	public function get_data(): array {
		$data = [];

		$data[ 'component_classes' ] = $this->options[ static::COMPONENT_CLASS ];
		$data[ 'img' ]               = $this->get_image();
		$data[ 'wrapper' ]           = $this->get_wrapper();
		$data[ static::LINK ]        = $this->get_link();
		$data[ static::HTML ]        = ! empty( $this->options[ static::HTML ] ) ? $this->options[ static::HTML ] : '';

		return $data;
	}

	protected function should_lazy_load(): bool {
		$has_image_id_or_path = ( ! empty( $this->options[ static::IMG_ID ] ) || ! empty( $this->options[ static::IMG_URL ] ) );

		return $this->options[ static::USE_LAZYLOAD ] && ! $this->options[ static::AS_BG ] && $has_image_id_or_path;
	}

	protected function get_image(): array {

		if ( $this->options[ static::AS_BG ] ) {
			return [];
		}

		return [
			'attributes' => $this->get_attributes(),
			'class'      => $this->should_lazy_load() ? $this->options[ static::IMG_CLASS ] . ' lazyload' : $this->options[ static::IMG_CLASS ],
		];
	}

	protected function get_wrapper(): array {

		if ( ! $this->options[ static::USE_WRAPPER ] && ! $this->options[ static::AS_BG ] ) {
			return [];
		}

		return [
			'tag'        => empty( $this->options[ static::WRAPPER_TAG ] ) ? ( $this->options[ static::AS_BG ] ? 'div' : 'figure' ) : $this->options[ static::WRAPPER_TAG ],
			'attributes' => $this->options[ static::AS_BG ] ? $this->get_attributes() . ' ' . $this->options[ static::WRAPPER_ATTR ] : ' ' . $this->options[ static::WRAPPER_ATTR ],
			'class'      => $this->should_lazy_load() ? $this->options[ static::WRAPPER_CLASS ] . ' lazyload' : $this->options[ static::WRAPPER_CLASS ],
		];
	}

	protected function get_link(): array {
		if ( empty( $this->options[ static::LINK ] ) ) {
			return [];
		}

		return [
			'url'    => $this->options[ static::LINK ],
			'target' => $this->options[ static::LINK_TARGET ],
			'title'  => ! empty( $this->options[ static::LINK_TITLE ] ) ? $this->options[ static::LINK_TITLE ] : '',
			'class'  => ! empty( $this->options[ static::LINK_CLASS ] ) ? $this->options[ static::LINK_CLASS ] : '',
			'rel'    => $this->options[ static::LINK_TARGET ] === '_blank' ? 'rel="noopener"' : '',
		];
	}

	/**
	 * Util to set item attributes for lazyload or not, bg or not
	 *
	 * @return string
	 */
	private function get_attributes(): string {
		$has_image_id = $this->options[ static::IMG_ID ] !== 0;
		$src          = '';
		$src_width    = '';
		$src_height   = '';
		// we'll almost always set src, except if for some reason they wanted to only use srcset
		$attrs = [];
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
		$attrs[] = ! empty( $this->options[ static::IMG_ATTR ] ) ? trim( $this->options[ static::IMG_ATTR ] ) : '';

		// the alt text
		$alt_text = $this->options[ static::IMG_ALT_TEXT ];

		// Check for a specific alt meta value on the image post, otherwise fallback to the image post's title.
		if ( empty( $alt_text ) && $has_image_id ) {
			$alt_meta_value = get_post_meta( $this->options[ static::IMG_ID ], '_wp_attachment_image_alt', true );
			$alt_text       = ! empty( $alt_meta_value ) ? $alt_meta_value : get_the_title( $this->options[ static::IMG_ID ] );
		}

		$attrs[] = $this->options[ static::AS_BG ] ? sprintf( 'role="img" aria-label="%s"', $alt_text ) : sprintf( 'alt="%s"', $alt_text );

		if ( $this->options[ static::USE_LAZYLOAD ] ) {

			// the expand attribute that controls threshold
			$attrs[] = sprintf( 'data-expand="%s"', $this->options[ static::EXPAND ] );

			// the parent fit attribute if as_bg is used.
			$attrs[] = ! $this->options[ static::AS_BG ] ? sprintf( 'data-parent-fit="%s"', $this->options[ static::PARENT_FIT ] ) : '';

			// set an src if true in options, since lazyloading this is "data-src"
			$attrs[] = ! $this->options[ static::AS_BG ] && $this->options[ static::SRC ] ? sprintf( 'data-src="%s"', $src ) : '';

			// the shim attribute for srcset.
			$shim_src = $this->get_shim();
			if ( ! $this->options[ static::AS_BG ] && $this->options[ static::USE_SRCSET ] && ! empty( $this->options[ static::SRCSET_SIZES ] ) ) {
				$attrs[] = sprintf( 'srcset="%s"', $shim_src );
			}

			// the sizes attribute for srcset
			if ( $this->options[ static::USE_SRCSET ] && ! empty( $this->options[ static::SRCSET_SIZES ] ) ) {
				$sizes_value = $this->options[ static::AUTO_SIZES_ATTR ] ? 'auto' : $this->options[ static::SRCSET_SIZES__ATTR ];
				$attrs[]     = sprintf( 'data-sizes="%s"', $sizes_value );
			}

			// generate the srcset attribute if wanted
			if ( $this->options[ static::USE_SRCSET ] && ! empty( $this->options[ static::SRCSET_SIZES ] ) ) {
				$attribute_name = $this->options[ static::AS_BG ] ? 'data-bgset' : 'data-srcset';
				$srcset_urls    = $this->get_srcset_attribute();
				$attrs[]        = sprintf( '%s="%s"', $attribute_name, $srcset_urls );
			}
			// setup the shim
			if ( $this->options[ static::AS_BG ] ) {
				$attrs[] = sprintf( 'style="background-image:url(\'%s\');"', $shim_src );
			} else {
				$attrs[] = sprintf( 'src="%s"', $shim_src );
			}
		} else {

			// no lazyloading, standard stuffs
			if ( $this->options[ static::AS_BG ] ) {
				$attrs[] = sprintf( 'style="background-image:url(\'%s\');"', $src );
			} else {
				$attrs[] = $this->options[ static::SRC ] ? sprintf( 'src="%s"', $src ) : '';
				if ( $this->options[ static::USE_SRCSET ] && ! empty( $this->options[ static::SRCSET_SIZES ] ) ) {
					$srcset_urls = $this->get_srcset_attribute();
					$attrs[]     = sprintf( 'sizes="%s"', $this->options[ static::SRCSET_SIZES__ATTR ] );
					$attrs[]     = sprintf( 'srcset="%s"', $srcset_urls );
				}
				if ( $this->options[ static::USE_HW_ATTR ] && $this->options[ static::SRC ] ) {
					$attrs[] = sprintf( 'width="%s"', $src_width / 2 );
					$attrs[] = sprintf( 'height="%s"', $src_height / 2 );
				}
			}
		}

		return implode( ' ', $attrs );
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
			// Don't add nonexistent intermediate sizes to the src_set. It ends up being the full-size URL.
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
