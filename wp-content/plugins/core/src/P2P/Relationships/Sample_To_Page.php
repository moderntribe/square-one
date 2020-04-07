<?php

namespace Tribe\Project\P2P\Relationships;

use Tribe\Libs\P2P\Relationship;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Sample\Sample;

class Sample_To_Page extends Relationship {

	const NAME = 'Sample_To_Page';

	protected $from = [
		Sample::NAME,
	];

	protected $to = [
		Page::NAME,
	];

	public function get_args() {
		return [
			'reciprocal'      => true,
			'cardinality'     => 'many-to-many',
			'admin_box'       => [
				'show'    => 'any',
				'context' => 'side',
			],
			'title'           => [
				'from' => __( 'Related Pages', 'tribe' ),
				'to'   => __( 'Related Samples', 'tribe' ),
			],
			'from_labels'     => [
				'singular_name' => __( 'Sample', 'tribe' ),
				'search_items'  => __( 'Search', 'tribe' ),
				'not_found'     => __( 'Nothing found.', 'tribe' ),
				'create'        => __( 'Relate Sample', 'tribe' ),
			],
			'to_labels'       => [
				'singular_name' => __( 'Page', 'tribe' ),
				'search_items'  => __( 'Search', 'tribe' ),
				'not_found'     => __( 'Nothing found.', 'tribe' ),
				'create'        => __( 'Relate Page', 'tribe' ),
			],
			'can_create_post' => false,
		];
	}
}
