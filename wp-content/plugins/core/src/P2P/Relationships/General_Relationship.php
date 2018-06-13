<?php


namespace Tribe\Project\P2P\Relationships;


use Tribe\Project\P2P\Relationship;

class General_Relationship extends Relationship {
	const NAME = 'related_posts';

	protected function get_args() {
		return [
			'reciprocal' => true,
			'admin_box' => [
				'show' => 'any',
				'context' => 'side',
			],
			'title' => [
				'from' => __( 'Related', 'tribe' ),
				'to' => __( 'Related', 'tribe' ),
			],
			'from_labels' => [
				'singular_name' => __( 'Related', 'tribe' ),
				'search_items' => __( 'Search', 'tribe' ),
				'not_found' => __( 'Nothing found.', 'tribe' ),
				'create' => __( 'Create Relations', 'tribe' )
			],
			'to_labels' => [
				'singular_name' => __( 'Related', 'tribe' ),
				'search_items' => __( 'Search', 'tribe' ),
				'not_found' => __( 'Nothing found.', 'tribe' ),
				'create' => __( 'Create Relations', 'tribe' )
			],
			'can_create_post' => false,
		];
	}
}