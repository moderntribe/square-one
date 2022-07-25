<?php declare(strict_types=1);

namespace Tribe\Project\Theme\Media;

class Image_Attributes {

	/**
	 * Adjust when the `loading="lazy"` attribute is applied to image.
	 *
	 * Since we always want height & width attributes on images, even when not lazy loading them;
	 * and, we're using our own lazyload implementation, we only want to render the `loading` attribute
	 * for default WP image placements, not for any of our custom blocks using the image component.
	 *
	 * @param string|bool $value   The `loading` attribute value. Returning a falsey value will result in
	 *                             the attribute being omitted for the image.
	 * @param string      $image   The HTML `img` tag to be filtered.
	 * @param string      $context Additional context about how the function was called or where the img tag is.
	 *
	 * @filter wp_img_tag_add_loading_attr
	 *
	 * @return string|bool
	 */
	public function customize_wp_image_loading_attr( $value, string $image, string $context ) {
		if ( 'the_content' === $context && strpos( $image, 'c-image__image' ) ) {
			return false;
		}

		return $value;
	}

}
