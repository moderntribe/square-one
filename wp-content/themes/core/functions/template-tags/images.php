<?php

/**
 * Reusable lazyload image get/print with srcset support. Supports src, srcset,
 * background image or inline, linkify or not, html append. Tied into the js lib lazysizes for lazyloading.
 *
 * @param $image_id int
 * @param $options array
 *
 * @return string
 */

function the_tribe_image( $image_id = 0, $options = [] ) {

	$defaults = [
		'as_bg'             => false,             // us this as background on wrapper?
		'auto_shim'         => true,              // if true, shim dir as set will be used, src_size will be used as filename, with png as filetype
		'auto_sizes_attr'   => false,             // if lazyloading the lib can auto create sizes attribute.
		'echo'              => true,              // whether to echo or return the html
		'expand'            => '200',             // the expand attribute is the threshold used by lazysizes. use negative to reveal once in viewport.
		'html'              => '',                // append an html string in the wrapper
		'img_class'         => '',                // pass classes for image tag. if lazyload is true class "lazyload" is auto added
		'img_attr'          => '',                // additional image attributes
		'link'              => '',                // pass a link to wrap the image
		'link_target'       => '_self',           // pass a link target
		'parent_fit'        => 'width',           // if lazyloading this combines with object fit css and the object fit polyfill
		'shim'              => '',                // supply a manually specified shim for lazyloading. Will override auto_shim whether true/false.
		'src'               => true,              // set to false to disable the src attribute. this is a fallback for non srcset browsers
		'src_size'          => 'large',           // this is the main src registered image size
		'srcset_sizes'      => [],                // this is registered sizes array for srcset.
		'srcset_sizes_attr' => '(min-width: 1260px) 1260px, 100vw', // this is the srcset sizes attribute string used if auto is false.
		'use_lazyload'      => true,              // lazyload this game?
		'use_srcset'        => true,              // srcset this game?
		'use_wrapper'       => true,              // use the wrapper if image
		'wrapper_attr'      => '',                // additional wrapper attributes
		'wrapper_class'     => 'tribe-image',     // pass classes for figure wrapper. If as_bg is set true gets auto class of "lazyload"
		'wrapper_tag'       => '',                // html tag for the wrapper/background image container
	];

	$opts = wp_parse_args( $options, $defaults );

	// they didnt supply an image id

	if ( empty( $image_id ) ) {
		return '';
	}

	// get the html

	$html = get_the_image_html( $image_id, $opts );

	// echo or return

	if ( $opts['echo'] ) {
		echo $html;
	} else {
		return $html;
	}

}

/**
 * Util to set item attributes for lazyload or not, bg or not, used by the_tribe_image.
 *
 * @param $image_id int
 * @param $options array
 *
 * @return string
 */

function get_the_item_attributes( $image_id = 0, $options = [ ] ) {

	$src = '';
	// we'll almost always set src, except if for some reason they wanted to only use srcset
	if( $options['src'] ){
		$src = wp_get_attachment_image_src( $image_id, $options['src_size'] );
		$src = $src[0];
	}
	$additional_attr = ! empty( $options['img_attr'] ) ? sprintf( ' %s ', trim( $options['img_attr'] ) ) : '';
	$img_alt_attr = $options['as_bg'] ? '' : sprintf( ' alt="%s" ', get_the_title( $image_id ) );
	$srcset_attr = '';
	$sizes_attr = '';

	if ( $options['use_lazyload'] ) {

		// get the shim
		$shim_src = get_the_shim( $options );

		// the expand attribute that controls threshold
		$expand_attr = sprintf( ' data-expand="%s" ', $options['expand'] );

		// the parent fit attribute if as_bg is used.
		$parent_fit_attr = ! $options['as_bg'] ? sprintf( ' data-parent-fit="%s" ', $options['parent_fit'] ) : '';

		// set an src if true in options, since lazyloading this is "data-src"
		$src_attr = ! $options['as_bg'] && $options['src'] ? sprintf( ' data-src="%s" ', $src ) : '';

		// the shim attribute for srcset.
		$srcset_shim_attr = '';

		if ( ! $options['as_bg'] && $options['use_srcset'] && ! empty( $options['srcset_sizes'] ) ) {
			$srcset_shim_attr = sprintf( ' srcset="%s" ', $shim_src );
		}

		if ( $options['use_srcset'] && ! empty( $options['srcset_sizes'] ) ) {
			$sizes_value = $options['auto_sizes_attr'] ? 'auto' : $options['srcset_sizes_attr'];
			$sizes_attr = sprintf( ' data-sizes="%s" ', $sizes_value );
		}

		// generate the srcset attribute if wanted
		if ( $options['use_srcset'] && ! empty( $options['srcset_sizes'] ) ) {
			$attribute_name = $options['as_bg'] ? 'data-bgset' : 'data-srcset';
			$srcset_urls = get_the_srcset_attribute( $image_id, $options['srcset_sizes'] );
			$srcset_attr = sprintf( ' %s="%s" ', $attribute_name, $srcset_urls );
		}

		if ( $options['as_bg'] ) {

			$shim_attr = sprintf( ' style="background-image:url(\'%s\');" ', $shim_src );
		} else {

			$shim_attr = sprintf( ' src="%s" ', $shim_src );
		}
		return sprintf(
			'%s%s%s%s%s%s%s%s%s',
			$shim_attr,
			$srcset_shim_attr,
			$src_attr,
			$srcset_attr,
			$sizes_attr,
			$expand_attr,
			$parent_fit_attr,
			$img_alt_attr,
			$additional_attr
		);

	} else {

		// no lazyloading, standard stuffs

		if ( $options['as_bg'] ) {

			return sprintf( ' style="background-image:url(\'%s\');" %s', $src, $additional_attr );
		} else {

			if ( $options['use_srcset'] && ! empty( $options['srcset_sizes'] ) ) {
				$sizes_attr = sprintf( ' sizes="%s" ', $options['srcset_sizes_attr'] );
				$srcset_urls = get_the_srcset_attribute( $image_id, $options['srcset_sizes'] );
				$srcset_attr = sprintf( ' srcset="%s" ', $srcset_urls );
			}

			return sprintf( ' src="%s"%s%s%s%s', $src, $srcset_attr, $sizes_attr, $img_alt_attr, $additional_attr );
		}
	}
}

/**
 * Returns shim src for lazyloading on request. Auto shim uses size name to lookup png file
 * in shims directory.
 *
 * @param $options array
 *
 * @return string
 */

function get_the_shim( $options = [] ) {

	$shim_dir = trailingslashit( get_template_directory_uri() ) . 'img/shims/';
	$src = $options[ 'shim' ];

	if ( empty ( $options[ 'shim' ] ) ) {
		if ( $options[ 'auto_shim' ] ) {
			$src = $shim_dir . $options[ 'src_size' ] . '.png';
		} else {
			$src = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
		}
	}

	return $src;
}

/**
 * Loops over supplied wp registered sizes and forms a valid srcset string with width and height values
 *
 * @param $image_id int
 * @param $sizes array
 *
 * @return string
 */

function get_the_srcset_attribute( $image_id = 0, $sizes = [] ){

	$attribute = '';
	$i = 1;
	$length = count( $sizes );
	foreach( $sizes as $size ){
		$src = wp_get_attachment_image_src( $image_id, $size );
		$divider = $i === $length ? '' : ',';
		$attribute .= sprintf( '%s %sw %sh %s', $src[0], $src[1], $src[2], $divider ) . "\n";
		$i++;
	}

	return $attribute;
}

/**
 * Forms the html that is output by the_tribe_image.
 *
 * @param $image_id int
 * @param $options array
 *
 * @return string
 */

function get_the_image_html( $image_id = 0, $options = [ ] ) {

	$html = '';

	if( empty( $options['wrapper_tag'] ) ){
		$tag = $options['as_bg'] ? 'div' : 'figure';
	} else {
		$tag = $options['wrapper_tag'];
	}
	$img_attributes = get_the_item_attributes( $image_id, $options );
	$img_class     = $options['use_lazyload'] && ! $options['as_bg'] && ! empty( $image_id ) ? $options['img_class'] . ' lazyload' : $options['img_class'];

	// start the html

	// open wrapper
	if( $options['use_wrapper'] || $options['as_bg'] ){
		$wrapper_class = $options['use_lazyload'] && $options['as_bg'] && ! empty( $image_id ) ? $options['wrapper_class'] . ' lazyload' : $options['wrapper_class'];
		$html .= '<' . $tag;
		$html .= $options['as_bg'] ? $img_attributes : ' ' . $options['wrapper_attr'];
		$html .= sprintf( ' class="%s">', $wrapper_class );
	}

	// maybe link open
	$html .= ! empty( $options['link'] ) ? sprintf( '<a href="%s" target="%s">', $options['link'], $options['link_target'] ) : '';

	// maybe img
	$html .= ! $options['as_bg'] ? sprintf( '<img class="%s"%s />', $img_class, $img_attributes ) : '';

	// maybe link close
	$html .= ! empty( $options['link'] ) ? '</a>' : '';

	// append arbitrary html
	$html .= $options['html'];

	// close wrapper
	if( $options['use_wrapper'] || $options['as_bg'] ) {
		$html .= sprintf( '</%s>', $tag );
	}

	return $html;

}