<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Content\Search;

use Tribe\Project\Templates\Controllers\Content\Loop\Item as Loop_Item;

class Item extends Loop_Item {
	protected $path = 'content/search/item.twig';

	public function get_data(): array {
		$data['post'] = [
			'post_type'      => get_post_type(),
			'title'          => get_the_title(),
			'content'        => apply_filters( 'the_content', get_the_content() ),
			'excerpt'        => apply_filters( 'the_excerpt', get_the_excerpt() ),
			'permalink'      => get_the_permalink(),
			'featured_image' => $this->get_featured_image(),
		];

		return $data;
	}

	protected function get_featured_image() {
		$options = [
			'wrapper_class' => 'item-loop__image',
			'link'          => get_permalink(),
			'echo'          => false,
		];

		return the_tribe_image( get_post_thumbnail_id(), $options );
	}
}
