<?php declare(strict_types=1);

namespace Tribe\Project\P2P\Relationships;

use Tribe\Libs\P2P\Relationship;
use Tribe\Project\Post_Types\Event\Event;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
class General_Relationship extends Relationship {

	public const NAME = 'related_posts';

	/**
	 * @var string[]
	 */
	protected $from = [
		Page::NAME,
		Post::NAME,
		Event::NAME,
	];

	/**
	 * @var string[]
	 */
	protected $to = [
		Page::NAME,
		Post::NAME,
		Event::NAME,
	];

	protected function get_args(): array {
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

}
