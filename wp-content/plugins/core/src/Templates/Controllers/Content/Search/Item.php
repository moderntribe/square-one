<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Content\Search;

use Tribe\Project\Templates\Components\Content\Search_Item;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Controllers\Content\Loop\Item as Loop_Item;

class Item extends Loop_Item {
	public function render( string $path = '' ): string {
		return $this->factory->get( Search_Item::class, $this->get_data() )->render( $path );
	}

	public function get_data(): array {
		return [
			Search_Item::POST_TYPE => get_post_type(),
			Search_Item::TITLE     => get_the_title(),
			Search_Item::EXCERPT   => apply_filters( 'the_excerpt', get_the_excerpt() ),
			Search_Item::PERMALINK => get_the_permalink(),
			Search_Item::IMAGE     => $this->get_featured_image(),
		];
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
