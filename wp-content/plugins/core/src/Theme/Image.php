<?php


namespace Tribe\Project\Theme;


class Image {
	private $image_id   = 0;
	private $image_path = ''; // $image_id takes precedence
	private $options    = [];

	public function __construct( $image_id, $image_path, $options ) {
		$this->image_id   = $image_id;
		$this->image_path = $image_path;
		$this->options    = $this->parse_args( $options );
	}

	public function option( $option ) {
		if ( isset( $this->options[ $option ] ) ) {
			return $this->options[ $option ];
		}
		return null;
	}

	private function parse_args( $options ) {
		$defaults = [
			'as_bg'             => false,             // us this as background on wrapper?
			'auto_shim'         => true,              // if true, shim dir as set will be used, src_size will be used as filename, with png as filetype
			'auto_sizes_attr'   => false,             // if lazyloading the lib can auto create sizes attribute.
			'echo'              => true,              // whether to echo or return the html
			'expand'            => '200',             // the expand attribute is the threshold used by lazysizes. use negative to reveal once in viewport.
			'html'              => '',                // append an html string in the wrapper
			'img_class'         => '',                // pass classes for image tag. if lazyload is true class "lazyload" is auto added
			'img_attr'          => '',                // additional image attributes
			'img_alt_text'      => '',                // pass specific image alternate text. if not included, will default to image title
			'link'              => '',                // pass a link to wrap the image
			'link_class'        => '',                // pass link classes
			'link_target'       => '_self',           // pass a link target
			'link_title'        => '',                // pass a link title
			'parent_fit'        => 'width',           // if lazyloading this combines with object fit css and the object fit polyfill
			'shim'              => '',                // supply a manually specified shim for lazyloading. Will override auto_shim whether true/false.
			'src'               => true,              // set to false to disable the src attribute. this is a fallback for non srcset browsers
			'src_size'          => 'large',           // this is the main src registered image size
			'srcset_sizes'      => [],                // this is registered sizes array for srcset.
			'srcset_sizes_attr' => '(min-width: 1260px) 1260px, 100vw', // this is the srcset sizes attribute string used if auto is false.
			'use_h&w_attr'      => false,             // this will set the width and height attributes on the img to be half the origal for retina/hdpi. Only for not lazyloading and when src exists.
			'use_lazyload'      => true,              // lazyload this game?
			'use_srcset'        => true,              // srcset this game?
			'use_wrapper'       => true,              // use the wrapper if image
			'wrapper_attr'      => '',                // additional wrapper attributes
			'wrapper_class'     => 'tribe-image',     // pass classes for figure wrapper. If as_bg is set true gets auto class of "lazyload"
			'wrapper_tag'       => '',                // html tag for the wrapper/background image container
		];

		return wp_parse_args( $options, $defaults );
	}

	/**
	 * Forms the html for the image
	 *
	 * @return string
	 */
	public function render() {

		$html = '';

		if ( empty( $this->options[ 'wrapper_tag' ] ) ) {
			$tag = $this->options[ 'as_bg' ] ? 'div' : 'figure';
		} else {
			$tag = $this->options[ 'wrapper_tag' ];
		}
		$img_attributes = $this->get_attributes();
		$img_class      = $this->options[ 'use_lazyload' ] && ! $this->options[ 'as_bg' ] && ( ! empty( $this->image_id ) || ! empty( $this->image_path ) ) ? $this->options[ 'img_class' ] . ' lazyload' : $this->options[ 'img_class' ];

		// start the html

		// open wrapper
		if ( $this->options[ 'use_wrapper' ] || $this->options[ 'as_bg' ] ) {
			$wrapper_class = $this->options[ 'use_lazyload' ] && $this->options[ 'as_bg' ] && ( ! empty( $this->image_id ) || ! empty( $this->image_path ) ) ? $this->options[ 'wrapper_class' ] . ' lazyload' : $this->options[ 'wrapper_class' ];
			$html          .= '<' . $tag;
			$html          .= $this->options[ 'as_bg' ] ? $img_attributes . ' ' . $this->options[ 'wrapper_attr' ] : ' ' . $this->options[ 'wrapper_attr' ];
			$html          .= sprintf( ' class="%s">', $wrapper_class );
		}

		// maybe link open
		$html .= ! empty( $this->options[ 'link' ] ) ? sprintf( '<a href="%s" target="%s"%s%s>', $this->options[ 'link' ], $this->options[ 'link_target' ], ! empty( $this->options[ 'link_title' ] ) ? ' title="' . $this->options[ 'link_title' ] . '"' : '', ! empty( $this->options[ 'link_class' ] ) ? ' class="' . $this->options[ 'link_class' ] . '"' : '' ) : '';

		// maybe img
		$html .= ! $this->options[ 'as_bg' ] ? sprintf( '<img class="%s"%s />', $img_class, $img_attributes ) : '';

		// maybe link close
		$html .= ! empty( $this->options[ 'link' ] ) ? '</a>' : '';

		// append arbitrary html
		$html .= $this->options[ 'html' ];

		// close wrapper
		if ( $this->options[ 'use_wrapper' ] || $this->options[ 'as_bg' ] ) {
			$html .= sprintf( '</%s>', $tag );
		}

		return $html;

	}

	/**
	 * Util to set item attributes for lazyload or not, bg or not
	 *
	 * @return string
	 */
	private function get_attributes() {

		$src        = '';
		$src_width  = '';
		$src_height = '';
		// we'll almost always set src, except if for some reason they wanted to only use srcset
		$attrs = [];
		if ( $this->options[ 'src' ] ) {
			if ( $this->image_id !== 0 ) { // image_id takes precedence
				$src        = wp_get_attachment_image_src( $this->image_id, $this->options[ 'src_size' ] );
				$src_width  = $src[ 1 ];
				$src_height = $src[ 2 ];
				$src        = $src[ 0 ];
			} else { // using image_path
				$src = $this->image_path;
			}
		}
		$attrs[] = ! empty( $this->options[ 'img_attr' ] ) ? trim( $this->options[ 'img_attr' ] ) : '';

		// the alt text
		$alt_text = $this->options[ 'img_alt_text' ];

		// Check for a specific alt meta value on the image post, otherwise fallback to the image post's title.
		if ( empty( $alt_text ) && $this->image_id !== 0 ) {
			$alt_meta_value = get_post_meta( $this->image_id, '_wp_attachment_image_alt', true );
			$alt_text       = ! empty( $alt_meta_value ) ? $alt_meta_value : get_the_title( $this->image_id );
		}

		$attrs[] = $this->options[ 'as_bg' ] ? sprintf( 'role="img" aria-label="%s"', $alt_text ) : sprintf( 'alt="%s"', $alt_text );

		if ( $this->options[ 'use_lazyload' ] ) {

			// the expand attribute that controls threshold
			$attrs[] = sprintf( 'data-expand="%s"', $this->options[ 'expand' ] );

			// the parent fit attribute if as_bg is used.
			$attrs[] = ! $this->options[ 'as_bg' ] ? sprintf( 'data-parent-fit="%s"', $this->options[ 'parent_fit' ] ) : '';

			// set an src if true in options, since lazyloading this is "data-src"
			$attrs[] = ! $this->options[ 'as_bg' ] && $this->options[ 'src' ] ? sprintf( 'data-src="%s"', $src ) : '';

			// the shim attribute for srcset.
			$shim_src = $this->get_shim();
			if ( ! $this->options[ 'as_bg' ] && $this->options[ 'use_srcset' ] && ! empty( $this->options[ 'srcset_sizes' ] ) ) {
				$attrs[] = sprintf( 'srcset="%s"', $shim_src );
			}

			// the sizes attribute for srcset
			if ( $this->options[ 'use_srcset' ] && ! empty( $this->options[ 'srcset_sizes' ] ) ) {
				$sizes_value = $this->options[ 'auto_sizes_attr' ] ? 'auto' : $this->options[ 'srcset_sizes_attr' ];
				$attrs[]     = sprintf( 'data-sizes="%s"', $sizes_value );
			}

			// generate the srcset attribute if wanted
			if ( $this->options[ 'use_srcset' ] && ! empty( $this->options[ 'srcset_sizes' ] ) ) {
				$attribute_name = $this->options[ 'as_bg' ] ? 'data-bgset' : 'data-srcset';
				$srcset_urls    = $this->get_srcset_attribute();
				$attrs[]        = sprintf( '%s="%s"', $attribute_name, $srcset_urls );
			}
			// setup the shim
			if ( $this->options[ 'as_bg' ] ) {
				$attrs[] = sprintf( 'style="background-image:url(\'%s\');"', $shim_src );
			} else {
				$attrs[] = sprintf( 'src="%s"', $shim_src );
			}
		} else {

			// no lazyloading, standard stuffs
			if ( $this->options[ 'as_bg' ] ) {
				$attrs[] = sprintf( 'style="background-image:url(\'%s\');"', $src );
			} else {
				$attrs[] = $this->options[ 'src' ] ? sprintf( 'src="%s"', $src ) : '';
				if ( $this->options[ 'use_srcset' ] && ! empty( $this->options[ 'srcset_sizes' ] ) ) {
					$srcset_urls = $this->get_srcset_attribute();
					$attrs[]     = sprintf( 'sizes="%s"', $this->options[ 'srcset_sizes_attr' ] );
					$attrs[]     = sprintf( 'srcset="%s"', $srcset_urls );
				}
				if ( $this->options[ 'use_h&w_attr' ] && $this->options[ 'src' ] ) {
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
	private function get_shim() {

		$shim_dir = trailingslashit( get_stylesheet_directory_uri() ) . 'img/theme/shims';
		$src      = $this->options[ 'shim' ];

		if ( empty ( $this->options[ 'shim' ] ) ) {
			if ( $this->options[ 'auto_shim' ] ) {
				$src = $shim_dir . $this->options[ 'src_size' ] . '.png';
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
	private function get_srcset_attribute() {

		$attribute = [];
		foreach ( $this->options[ 'srcset_sizes' ] as $size ) {
			$src = wp_get_attachment_image_src( $this->image_id, $size );
			// Don't add nonexistent intermediate sizes to the src_set. It ends up being the full-size URL.
			if ( 'full' !== $size && true === $src[ 3 ] ) {
				$attribute[] = sprintf( '%s %dw %dh', $src[ 0 ], $src[ 1 ], $src[ 2 ] );
			}
		}

		if ( empty( $attribute ) ) {
			$src         = wp_get_attachment_image_src( $this->image_id, 'full' );
			$attribute[] = sprintf( '%s %dw %dh', $src[ 0 ], $src[ 1 ], $src[ 2 ] );
		}

		return implode( ", \n", $attribute );
	}

}
