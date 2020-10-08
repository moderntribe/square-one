<?php

namespace Tribe\Project\Theme\Media;

class SVG_Filters {
	/**
	 * Filters the image src result.
	 *
	 * The Safe SVG plugin sets the width & height attributes to false.
	 * This causes our FE srcset values to be invalid for lazysizes.
	 *
	 * WordPress saves the correct SVG image dimensions in the image's full-size attributes.
	 * We're going to use the full-size values and set the correct values based on the
	 * requested size's aspect ratio.
	 *
	 * Note: This code will ALWAYS scale (not crop) the SVG to fit the requested size.
	 * If your use-case requires cropping SVG images, then this filter should be disabled.
	 *
	 * @param array|false  $image         Either array with src, width & height, icon src, or false.
	 * @param int          $attachment_id Image attachment ID.
	 * @param string|array $size          Size of image. Image size or array of width and height values (in that
	 *                                    order). Default 'thumbnail'.
	 * @param bool         $icon          Whether the image should be treated as an icon. Default false.
	 *
	 * @return array
	 * @filter wp_get_attachment_image_src
	 */
	public function set_accurate_sizes( $image, $attachment_id, $size, $icon ) {
		if ( get_post_mime_type( $attachment_id ) !== 'image/svg+xml' ) {
			return $image;
		}

		$meta = wp_get_attachment_metadata( $attachment_id );

		if ( is_array( $size ) ) {
			// If a specific width & height are requested, just use them
			$requested_size['width']  = $size[0];
			$requested_size['height'] = $size[1];
		} else {
			// Otherwise check for the requested size key in the available sizes.
			$requested_size = isset( $meta[ 'sizes' ][ $size ] ) ? $meta[ 'sizes' ][ $size ] : false;

			if ( ! $requested_size ) {
				return $image;
			}
		}

		// Width
		$image[1] = intval( $requested_size['width'] );

		// Height
		$image[2] = intval( $requested_size['width'] * $meta['height'] / $meta['width'] );

		return $image;
	}
}
