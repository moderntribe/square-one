<?php
/**
 * Template Tags: Content Output
 */


/**
 * Sets up the proper title
 *
 * @since core 1.0
 * @return string
 */

function get_page_title() {
	$title = new \Tribe\Project\Theme\Page_Title();
	return $title->get_title();
}


/**
 * Output proper title
 *
 * @since core 1.0
 * @return string
 */

function the_page_title( $wrapper = true ) {

	$title = get_page_title();

	if ( $wrapper ) {
		$title = '<h1 class="page-title">' . $title . '</h1>';
	}

	if ( ! empty( $title ) ) {
		echo $title;
	}

}


/**
 * Returns an image, has various options
 *
 * @param null   $attachment_id
 * @param string $size
 * @param bool   $lazyload
 * @param bool   $shim
 * @param bool   $classes
 * @param null   $mobile_size
 * @param null   $retina_size
 *
 * @return string|void
 *
 * @since core 1.0
 */

function get_featured_image(
	$attachment_id = null,
	$size          = 'core-full',
	$lazyload      = true,
	$shim          = false,
	$classes       = false,
	$mobile_size   = null,
	$retina_size   = null
) {

	if ( empty( $attachment_id ) ) {
		$attachment_id = get_the_ID();
	}

	$image_src = $image_src_mobile = $image_src_retina = '';
	$class_lazyload = ( $lazyload ) ? 'lazyload' : '';
	$shim_path      = trailingslashit( get_template_directory_uri() ) . 'img/shims/';
	$image          = wp_get_attachment_image_src( $attachment_id, $size );
	$alt            = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
	$alt_text       = ( $alt ) ? $alt : get_the_title( $attachment_id );

	if ( empty( $image ) )
		return;

	// Size: Mobile
	if ( $mobile_size ) {
		$image_src_mobile = wp_get_attachment_image_src( $post_id, $mobile_size );
	}

	// Size: Retina
	if ( $retina_size ) {
		$image_src_retina = wp_get_attachment_image_src( $attachment_id, $retina_size );
	}

	// If using lazyload and have a shim, use that as the image src
	if ( $lazyload && $shim ) {
		$image_src = $shim_path . $shim;
	}

	// If not using lazyload, use regular image as the image src
	if ( ! $lazyload ) {
		$image_src = $image[0];
	}

	return sprintf(
		'<img %1$s%2$s%3$s%4$s%5$s alt="%6$s" />',
		( ! empty( $image_src ) ) ? 'src="'. esc_attr( $image_src ) .'"' : '',
		( $lazyload ) ? ' data-src="'. esc_attr( $image[0] ) .'" width="'. $image[1] .'" height="'. $image[2] .'"' : '',
		( $mobile_size ) ? ' data-mobile-src="'. esc_attr( $image_src_mobile[0] ) .'"' : '',
		( $retina_size ) ? ' data-retina-src="'. esc_attr( $image_src_retina[0] ) .'"' : '',
		( $classes || $lazyload ) ? ' class="'. $class_lazyload . ' ' . $classes .'"': '',
		esc_attr( $alt_text )
	);

}