<?php

/**
 * Reusable lazyload image get/print with srcset support. Supports src, srcset,
 * background image or inline, linkify or not, html append. Tied into the js lib
 * lazysizes for lazyloading.
 *
 * @param $image_id int
 * @param $options array
 *
 * @return string
 */
function the_tribe_image( $image_id = 0, $image_path = '', $options = [] ) {

	// There is no image ID or image path
	if ( empty( $image_id ) && empty( $image_path ) ) {
		return '';
	}

	$image = new \Tribe\Project\Theme\Image( $image_id, $image_path, $options );
	$html  = $image->render();
	if ( $image->option( 'echo' ) ) {
		echo $html;
		return '';
	} else {
		return $html;
	}

}
