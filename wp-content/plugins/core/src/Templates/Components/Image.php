<?php


namespace Tribe\Project\Templates\Components;

class Image extends Component {

	protected $path = 'components/image.twig';

	const ATTACHMENT         = 'attachment';
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
			self::ATTACHMENT         => null,
			// the WordPress attachment to use - takes precedence over IMG_URL
			self::IMG_URL            => '',
			// the Image URL - use as an image URL as a fallback if no ATTACHMENT exists
			self::AS_BG              => false,
			// us this as background on wrapper?
			self::AUTO_SHIM          => true,
			// if true, shim dir as set will be used, src_size will be used as filename, with png as filetype
			self::AUTO_SIZES_ATTR    => false,
			// pass a specific class to use for the component wrapper
			self::COMPONENT_CLASS    => '',
			// if lazyloading the lib can auto create sizes attribute.
			self::EXPAND             => '200',
			// the expand attribute is the threshold used by lazysizes. use negative to reveal once in viewport.
			self::HTML               => '',
			// append an html string in the wrapper
			self::IMG_CLASS          => '',
			// pass classes for image tag. if lazyload is true class "lazyload" is auto added
			self::IMG_ATTR           => '',
			// additional image attributes
			self::IMG_ALT_TEXT       => '',
			// pass specific image alternate text. if not included, will default to image title
			self::LINK               => '',
			// pass a link to wrap the image
			self::LINK_CLASS         => '',
			// pass link classes
			self::LINK_TARGET        => '_self',
			// pass a link target
			self::LINK_TITLE         => '',
			// pass a link title
			self::PARENT_FIT         => 'width',
			// if lazyloading this combines with object fit css and the object fit polyfill
			self::SHIM               => '',
			// supply a manually specified shim for lazyloading. Will override auto_shim whether true/false.
			self::SRC                => true,
			// set to false to disable the src attribute. this is a fallback for non srcset browsers
			self::SRC_SIZE           => 'large',
			// this is the main src registered image size
			self::SRCSET_SIZES       => [],
			// this is registered sizes array for srcset.
			self::SRCSET_SIZES__ATTR => '(min-width: 1260px) 1260px, 100vw',
			// this is the srcset sizes attribute string used if auto is false.
			self::USE_HW_ATTR        => false,
			// this will set the width and height attributes on the img to be half the origal for retina/hdpi. Only for not lazyloading and when src exists.
			self::USE_LAZYLOAD       => true,
			// lazyload this game?
			self::USE_SRCSET         => true,
			// srcset this game?
			self::USE_WRAPPER        => true,
			// use the wrapper if image
			self::WRAPPER_ATTR       => '',
			// additional wrapper attributes
			self::WRAPPER_CLASS      => 'tribe-image',
			// pass classes for figure wrapper. If as_bg is set true gets auto class of "lazyload"
			self::WRAPPER_TAG        => '',
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

		$data['component_classes'] = $this->options[ self::COMPONENT_CLASS ];
		$data['img']               = $this->get_image();
		$data['wrapper']           = $this->get_wrapper();
		$data[ self::LINK ]        = $this->get_link();
		$data[ self::HTML ]        = ! empty( $this->options[ self::HTML ] ) ? $this->options[ self::HTML ] : '';

		return $data;
	}

	protected function should_lazy_load(): bool {
		$has_attachment_or_path = ( ! empty( $this->options[ self::ATTACHMENT ] ) || ! empty( $this->options[ self::IMG_URL ] ) );

		return $this->options[ self::USE_LAZYLOAD ] && ! $this->options[ self::AS_BG ] && $has_attachment_or_path;
	}

	protected function get_image(): array {

		if ( $this->options[ self::AS_BG ] ) {
			return [];
		}

		return [
			'attributes' => $this->get_attributes(),
			'class'      => $this->should_lazy_load() ? $this->options[ self::IMG_CLASS ] . ' lazyload' : $this->options[ self::IMG_CLASS ],
		];
	}

	protected function get_wrapper(): array {

		if ( ! $this->options[ self::USE_WRAPPER ] && ! $this->options[ self::AS_BG ] ) {
			return [];
		}

		return [
			'tag'        => empty( $this->options[ self::WRAPPER_TAG ] ) ? ( $this->options[ self::AS_BG ] ? 'div' : 'figure' ) : $this->options[ self::WRAPPER_TAG ],
			'attributes' => $this->options[ self::AS_BG ] ? $this->get_attributes() . ' ' . $this->options[ self::WRAPPER_ATTR ] : ' ' . $this->options[ self::WRAPPER_ATTR ],
			'class'      => $this->should_lazy_load() ? $this->options[ self::WRAPPER_CLASS ] . ' lazyload' : $this->options[ self::WRAPPER_CLASS ],
		];
	}

	protected function get_link(): array {
		if ( empty( $this->options[ self::LINK ] ) ) {
			return [];
		}

		return [
			'url'    => $this->options[ self::LINK ],
			'target' => $this->options[ self::LINK_TARGET ],
			'title'  => ! empty( $this->options[ self::LINK_TITLE ] ) ? $this->options[ self::LINK_TITLE ] : '',
			'class'  => ! empty( $this->options[ self::LINK_CLASS ] ) ? $this->options[ self::LINK_CLASS ] : '',
			'rel'    => $this->options[ self::LINK_TARGET ] === '_blank' ? 'rel="noopener"' : '',
		];
	}

	/**
	 * Util to set item attributes for lazyload or not, bg or not
	 *
	 * @return string
	 */
	private function get_attributes(): string {
		/** @var \Tribe\Project\Templates\Models\Image $attachment */
		$attachment = $this->options[ self::ATTACHMENT ];
		$src_width  = '';
		$src_height = '';
		// we'll almost always set src, except if for some reason they wanted to only use srcset
		$attrs = [];
		if ( $attachment && $attachment->has_size( $this->options[ self::SRC_SIZE ] ) ) {
			$resized    = $attachment->get_size( $this->options[ self::SRC_SIZE ] );
			$src        = $resized->src;
			$src_width  = $resized->width;
			$src_height = $resized->height;
		} else {
			$src = $this->options[ self::IMG_URL ];
		}

		$attrs[] = ! empty( $this->options[ self::IMG_ATTR ] ) ? trim( $this->options[ self::IMG_ATTR ] ) : '';

		// the alt text
		$alt_text = $this->options[ self::IMG_ALT_TEXT ];

		// Check for a specific alt meta value on the image post, otherwise fallback to the image post's title.
		if ( empty( $alt_text ) && $attachment ) {
			$alt_text = $attachment->alt() ?: $attachment->title();
		}

		$attrs[] = $this->options[ self::AS_BG ] ? sprintf( 'role="img" aria-label="%s"', $alt_text ) : sprintf( 'alt="%s"', $alt_text );

		if ( $this->options[ self::USE_LAZYLOAD ] ) {

			// the expand attribute that controls threshold
			$attrs[] = sprintf( 'data-expand="%s"', $this->options[ self::EXPAND ] );

			// the parent fit attribute if as_bg is used.
			$attrs[] = ! $this->options[ self::AS_BG ] ? sprintf( 'data-parent-fit="%s"', $this->options[ self::PARENT_FIT ] ) : '';

			// set an src if true in options, since lazyloading this is "data-src"
			$attrs[] = ! $this->options[ self::AS_BG ] && $this->options[ self::SRC ] ? sprintf( 'data-src="%s"', $src ) : '';

			// the shim attribute for srcset.
			$shim_src = $this->get_shim();
			if ( ! $this->options[ self::AS_BG ] && $this->options[ self::USE_SRCSET ] && ! empty( $this->options[ self::SRCSET_SIZES ] ) ) {
				$attrs[] = sprintf( 'srcset="%s"', $shim_src );
			}

			// the sizes attribute for srcset
			if ( $this->options[ self::USE_SRCSET ] && ! empty( $this->options[ self::SRCSET_SIZES ] ) ) {
				$sizes_value = $this->options[ self::AUTO_SIZES_ATTR ] ? 'auto' : $this->options[ self::SRCSET_SIZES__ATTR ];
				$attrs[]     = sprintf( 'data-sizes="%s"', $sizes_value );
			}

			// generate the srcset attribute if wanted
			if ( $this->options[ self::USE_SRCSET ] && ! empty( $this->options[ self::SRCSET_SIZES ] ) ) {
				$attribute_name = $this->options[ self::AS_BG ] ? 'data-bgset' : 'data-srcset';
				$srcset_urls    = $this->get_srcset_attribute();
				$attrs[]        = sprintf( '%s="%s"', $attribute_name, $srcset_urls );
			}
			// setup the shim
			if ( $this->options[ self::AS_BG ] ) {
				$attrs[] = sprintf( 'style="background-image:url(\'%s\');"', $shim_src );
			} else {
				$attrs[] = sprintf( 'src="%s"', $shim_src );
			}
		} else {

			// no lazyloading, standard stuffs
			if ( $this->options[ self::AS_BG ] ) {
				$attrs[] = sprintf( 'style="background-image:url(\'%s\');"', $src );
			} else {
				$attrs[] = $this->options[ self::SRC ] ? sprintf( 'src="%s"', $src ) : '';
				if ( $this->options[ self::USE_SRCSET ] && ! empty( $this->options[ self::SRCSET_SIZES ] ) ) {
					$srcset_urls = $this->get_srcset_attribute();
					$attrs[]     = sprintf( 'sizes="%s"', $this->options[ self::SRCSET_SIZES__ATTR ] );
					$attrs[]     = sprintf( 'srcset="%s"', $srcset_urls );
				}
				if ( $this->options[ self::USE_HW_ATTR ] && $this->options[ self::SRC ] ) {
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
