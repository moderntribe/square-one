<?php declare(strict_types=1);

namespace Tribe\Project\Taxonomies\Example;

use Tribe\Libs\Taxonomy\Taxonomy_Config;
use Tribe\Project\Post_Types\Sample\Sample;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
class Config extends Taxonomy_Config {

	/**
	 * @var string
	 */
	protected $taxonomy = Example::NAME;

	/**
	 * @var string[]
	 */
	protected $post_types = [
		Sample::NAME,
	];

	/**
	 * @var int
	 */
	protected $version = 1;

	/**
	 * Arguments to pass when registering the taxonomy.
	 *
	 * @see register_extended_taxonomy() for accepted args.
	 *
	 * @return array
	 */
	public function get_args(): array {
		return [
			'hierarchical' => false,
			'exclusive'    => true,
			'meta_box'     => 'radio',
		];
	}

	public function get_labels(): array {
		return [
			'singular' => __( 'Example', 'tribe' ),
			'plural'   => __( 'Examples', 'tribe' ),
			'slug'     => __( 'examples', 'tribe' ),
		];
	}

	public function default_terms(): array {
		return [
			'first'  => __( 'First Default Term', 'tribe' ),
			'second' => __( 'Second Default Term', 'tribe' ),
		];
	}

}
