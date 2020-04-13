<?php
declare( strict_types=1 );

namespace Tribe\Project\Integrations\Yoast_SEO;

class Open_Graph {
	/**
	 * @var string
	 */
	private $image_size;

	public function __construct( string $image_size ) {
		$this->image_size = $image_size;
	}

	/**
	 * Filter the size used for Open Graph images
	 * @param string $size
	 *
	 * @return string
	 * @filter wpseo_opengraph_image_size
	 */
	public function customize_wpseo_image_size( $size ) {
		return $this->image_size;
	}
}
