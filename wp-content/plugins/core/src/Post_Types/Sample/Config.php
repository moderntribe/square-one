<?php declare(strict_types=1);

namespace Tribe\Project\Post_Types\Sample;

use Tribe\Libs\Post_Type\Post_Type_Config;

class Config extends Post_Type_Config {

	// phpcs:ignore SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
	protected $post_type = Sample::NAME;

	public function get_args(): array {
		return [
			'hierarchical'     => false,
			'enter_title_here' => __( 'Sample', 'tribe' ),
			'map_meta_cap'     => true,
			'supports'         => [ 'title', 'editor' ],
			//'capability_type'  => $this->post_type(), // for custom caps
			'capability_type'  => 'post', // to use default WP caps
		];
	}

	public function get_labels(): array {
		return [
			'singular' => __( 'Sample', 'tribe' ),
			'plural'   => __( 'Samples', 'tribe' ),
			'slug'     => __( 'samples', 'tribe' ),
		];
	}

}
