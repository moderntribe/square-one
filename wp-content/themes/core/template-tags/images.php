<?php

use Tribe\Project\Service_Providers\Twig_Service_Provider;
use Tribe\Project\Templates\Components\Image;

/**
 * Reusable lazyload image get/print with srcset support. Supports src, srcset,
 * background image or inline, linkify or not, html append. Tied into the js lib
 * lazysizes for lazyloading.
 *
 * @param $image_id int
 * @param $options array
 *
 * @return string|void
 */
function the_tribe_image( $image_id = 0, $options = [] ) {

	// There is no image ID or image path
	if ( empty( $image_id ) && empty( $options[ Image::IMG_URL ] ) ) {
		return '';
	}

	$options[ Image::IMG_ID ] = $image_id;

	if ( ! isset( $options[ Image::ECHO ] ) ) {
		$options[ Image::ECHO ] = true; // Mimic the image component default
	}

	$image_markup = tribe_project()->container()[ Twig_Service_Provider::COMPONENT_FACTORY ]->get( Image::class, $options )->render();

	if ( $options[ Image::ECHO ] ) {
		echo $image_markup;
	} else {
		return $image_markup;
	}
}
