<?php

use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Image;

/**
 * Reusable lazyload image get/print with srcset support. Supports src, srcset,
 * background image or inline, linkify or not, html append. Tied into the js lib
 * lazysizes for lazyloading.
 *
 * @param int   $image_id
 * @param array $options
 *
 * @return string
 */
function the_tribe_image( $image_id = 0, $options = [] ) {

	// There is no image ID or image path
	if ( empty( $image_id ) && empty( $options[ Image::IMG_URL ] ) ) {
		return '';
	}

	try {
		$options[ Image::ATTACHMENT ] = \Tribe\Project\Templates\Models\Image::factory( $image_id );

		return tribe_project()->container()->get( Component_Factory::class )->get( Image::class, $options )->render();
	} catch ( \Exception $e ) {
		return '';
	}
}
