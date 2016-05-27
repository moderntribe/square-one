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

	// they didn't supply an image id
	if ( empty( $image_id ) ) {
		return '';
	}

	$image = new \Tribe\Project\Theme\Image( $image_id, $options );
	$html = $image->render();
	if ( $image->option( 'echo' ) ) {
		echo $html;
		return '';
	} else {
		return $html;
	}

}
