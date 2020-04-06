<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Content;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Components\Content\Loop_Item as Item_Context;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Theme\Image_Sizes;

class Loop_Item extends Abstract_Template {
	protected $time_formats = [
		'c',
		'F j, Y',
	];

	public function render( string $path = '' ): string {
		return $this->factory->get( Item_Context::class, $this->get_data() )->render( $path );
	}

	public function get_data(): array {
		return [
			Item_Context::POST_TYPE => get_post_type(),
			Item_Context::TITLE     => get_the_title(),
			Item_Context::CONTENT   => apply_filters( 'the_content', get_the_content() ),
			Item_Context::EXCERPT   => apply_filters( 'the_excerpt', get_the_excerpt() ),
			Item_Context::PERMALINK => get_the_permalink(),
			Item_Context::IMAGE     => $this->get_featured_image(),
			Item_Context::TIMES     => $this->get_time(),
			Item_Context::AUTHOR    => $this->get_author(),
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
			Image::AS_BG           => true,
			Image::WRAPPER_CLASSES => [ 'item__image' ],
			Image::SHIM            => trailingslashit( get_stylesheet_directory_uri() ) . 'img/theme/shims/16x9.png',
			Image::SRC_SIZE        => Image_Sizes::CORE_FULL,
			Image::SRCSET_SIZES    => [
				Image_Sizes::CORE_FULL,
				Image_Sizes::CORE_MOBILE,
				Image_Sizes::SOCIAL_SHARE,
			],
		];

		return $this->factory->get( Image::class, $options )->render();
	}

	protected function get_time() {
		$times = [];
		foreach ( $this->time_formats as $format ) {
			$times[ $format ] = get_the_time( $format );
		}

		return $times;
	}

	protected function get_author() {
		return [
			'name' => get_the_author(),
			'url'  => get_author_posts_url( get_the_author_meta( 'ID' ) ),
		];
	}
}
