<?php


namespace Tribe\Project\P2P\Relationships;


use Tribe\Libs\P2P\Relationship;
use Tribe\Project\Post_Types\Event\Event;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;

class General_Relationship extends Relationship {
	public const NAME = 'related_posts';

	protected $from = [
		Page::NAME,
		Post::NAME,
		Event::NAME,
	];
	protected $to   = [
		Page::NAME,
		Post::NAME,
		Event::NAME,
	];

	public function __construct() {
		parent::__construct(
			array_filter( $this->from, [ $this, 'is_registered_post_type' ] ),
			array_filter( $this->to, [ $this, 'is_registered_post_type' ] )
		);
	}

	protected function get_args() {
		return [
			'reciprocal'      => true,
			'admin_box'       => [
				'show'    => 'any',
				'context' => 'side',
			],
			'title'           => [
				'from' => __( 'Related', 'tribe' ),
				'to'   => __( 'Related', 'tribe' ),
			],
			'from_labels'     => [
				'singular_name' => __( 'Related', 'tribe' ),
				'search_items'  => __( 'Search', 'tribe' ),
				'not_found'     => __( 'Nothing found.', 'tribe' ),
				'create'        => __( 'Create Relations', 'tribe' ),
			],
			'to_labels'       => [
				'singular_name' => __( 'Related', 'tribe' ),
				'search_items'  => __( 'Search', 'tribe' ),
				'not_found'     => __( 'Nothing found.', 'tribe' ),
				'create'        => __( 'Create Relations', 'tribe' ),
			],
			'can_create_post' => false,
		];
	}

	/**
	 * Registering a p2p connection type with an unregistered post type
	 * will throw an error.
	 *
	 * @param string $post_type
	 *
	 * @return bool Whether the post type is registered
	 */
	protected function is_registered_post_type( $post_type ) {
		return get_post_type_object( $post_type ) !== null;
	}
}
