<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Content\Search;

use Tribe\Project\Templates\Components\Image;
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
		$image_id = get_post_thumbnail_id();

		if ( empty( $image_id ) ) {
			return '';
		}

		try {
			$image = \Tribe\Project\Templates\Models\Image::factory( $image_id );
		} catch ( \Exception $e ) {
			return '';
		}

		$options = [
			Image::ATTACHMENT      => $image,
			Image::LINK_URL        => get_permalink(),
			Image::WRAPPER_CLASSES => [ 'item__image' ],
		];

		return $this->factory->get( Image::class, $options )->render();
	}
}
