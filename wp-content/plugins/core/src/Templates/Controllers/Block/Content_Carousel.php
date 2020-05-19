<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

use Tribe\Project\Blocks\Types\Content_Carousel as Carousel_Block;
use Tribe\Project\Templates\Components\Card;
use Tribe\Project\Templates\Components\Image as Image_Component;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Content_Carousel extends Block_Controller {
	public function render( string $path = '' ): string {
		return sprintf(
			'<div class="placeholder-block-%s">
				<h2>%s</h2>
				<div class="card-grid-description">%s</div>
				<div class="card-grid-cards"><h3>Cards</h3>%s</div>
			</div>',
			sanitize_html_class( $this->block_type->name() ),
			$this->get_title(),
			$this->get_description(),
			implode( '', $this->get_cards() )
		);
	}

	private function get_title(): string {
		return $this->attributes[ Carousel_Block::TITLE ] ?? '';
	}

	private function get_description(): string {
		return $this->attributes[ Carousel_Block::DESCRIPTION ] ?? '';
	}

	/**
	 * @return string[]
	 */
	private function get_cards(): array {
		if ( $this->is_select() ) {
			return $this->get_select_cards();
		}

		if ( $this->is_query() ) {
			return $this->get_query_cards();
		}

		return [];
	}

	private function is_query(): bool {
		return ! empty( $this->attributes['query'] );
	}

	private function is_select(): bool {
		return ! empty( $this->attributes['select'][0][ Carousel_Block::CARDS ] );
	}

	/**
	 * @return string[]
	 */
	private function get_select_cards(): array {
		return array_map( [ $this, 'build_select_card' ], $this->attributes['select'][0][ Carousel_Block::CARDS ] );
	}

	private function build_select_card( array $data ): string {
		// TODO: get a selected post instead of the latest one when we have a working post select field
		$query = new \WP_Query( [ 'post_type' => 'post', 'posts_per_page' => 1 ] );
		$post  = $query->get_posts()[0];

		$title    = empty( $data[ Carousel_Block::TITLE_OVERRIDE ] ) ? get_the_title( $post ) : $data[ Carousel_Block::TITLE_OVERRIDE ];
		$image_id = empty( $data[ Carousel_Block::IMAGE_OVERRIDE ] ) ? get_post_thumbnail_id( $post ) : $data[ Carousel_Block::IMAGE_OVERRIDE ];
		$link     = empty( $data[ Carousel_Block::LINK_OVERRIDE ]['url'] ) ? get_the_permalink( $post ) : $data[ Carousel_Block::LINK_OVERRIDE ]['url'];

		return $this->factory->get( Card::class, [
			Card::TITLE  => $title,
			Card::IMAGE  => $this->render_card_image( $image_id, $link ),
		] )->render();
	}

	/**
	 * @return string[]
	 */
	private function get_query_cards(): array {
		// TODO: build a real query when we have a working posts query field
		$query = new \WP_Query( [ 'post_type' => 'post', 'posts_per_page' => 12 ] );

		return array_map( function ( \WP_Post $post ) {
			return $this->factory->get( Card::class, [
				Card::TITLE => get_the_title( $post ),
				Card::IMAGE => $this->render_card_image( get_post_thumbnail_id( $post ), get_the_permalink( $post ) ),
			] )->render();
		}, $query->get_posts() );
	}

	private function render_card_image( $attachment_id, $link ): string {
		if ( empty( $attachment_id ) ) {
			return '';
		}
		try {
			return $this->factory->get( Link::class, [
				Link::CONTENT => $this->factory->get( Image_Component::class, [
					Image_Component::ATTACHMENT   => Image::factory( $attachment_id ),
					Image_Component::SRC_SIZE     => Image_Sizes::COMPONENT_CARD,
					Image_Component::USE_LAZYLOAD => false,
				] )->render(),
				Link::URL     => $link,
			] )->render();
		} catch ( \InvalidArgumentException $e ) {
			return '';
		}
	}
}
