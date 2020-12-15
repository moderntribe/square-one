<?php
declare( strict_types=1 );

namespace Tribe\Project\Integrations\Yoast_SEO;

use Tribe\Project\Theme\Config\Image_Sizes;

class Open_Graph {
	/**
	 * Filter the size used for Open Graph images
	 * @param string $size
	 *
	 * @return string
	 * @filter wpseo_opengraph_image_size
	 */
	public function customize_wpseo_opengraph_image_size( $size ) {
		return Image_Sizes::SOCIAL_SHARE_OPENGRAPH;
	}

	/**
	 * Filter the size used for Twitter images
	 * @param string $size
	 *
	 * @return string
	 * @filter wpseo_twitter_image_size
	 */
	public function customize_wpseo_twitter_image_size( $size ) {
		return Image_Sizes::SOCIAL_SHARE_TWITTER;
	}
}
