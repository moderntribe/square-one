<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Content;

use Exception;
use Tribe\Project\Templates\Components\Content\loop_item_search as Item_Context;
use Tribe\Project\Templates\Components\Image;

class Search_Item extends Loop_Item {
	public function render( string $path = '' ): string {
		return $this->factory->get( Item_Context::class, $this->get_data() )->render( $path );
	}

	public function get_data(): array {
		return [
			Item_Context::POST_TYPE => get_post_type(),
			Item_Context::TITLE     => get_the_title(),
			Item_Context::EXCERPT   => apply_filters( 'the_excerpt', get_the_excerpt() ),
			Item_Context::PERMALINK => get_the_permalink(),
			Item_Context::IMAGE     => $this->get_featured_image(),
		];
	}

	protected function get_featured_image() {
		$image_id = get_post_thumbnail_id();

		if ( empty( $image_id ) ) {
			return '';
		}

		try {
			$image = \Tribe\Project\Templates\Models\Image::factory( $image_id );
		} catch ( Exception $e ) {
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
