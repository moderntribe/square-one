<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

use Tribe\Project\Blocks\Types\Card_Grid as Card_Grid_Block;
use Tribe\Project\Templates\Components\Card;
use Tribe\Project\Templates\Components\Image as Image_Component;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Card_Grid extends Block_Controller {
	public function render( string $path = '' ): string {
		return sprintf(
			'<div class="placeholder-block-%s">
				<h2>%s</h2>
				<div class="card-grid-description">%s</div>
				<div class="card-grid-cta">%s</div>
				<div class="card-grid-cards"><h3>Cards</h3>%s</div>
			</div>',
			sanitize_html_class( $this->block_type->name() ),
			$this->get_title(),
			$this->get_description(),
			$this->get_cta(),
			implode( '', $this->get_cards() )
		);
	}

	private function get_title(): string {
		return $this->attributes[ Card_Grid_Block::TITLE ] ?? '';
	}

	private function get_description(): string {
		return $this->attributes[ Card_Grid_Block::DESCRIPTION ] ?? '';
	}

	private function get_cta(): string {
		$cta = wp_parse_args( $this->attributes[ Card_Grid_Block::CTA ] ?? [], [
			'text'   => '',
			'url'    => '',
			'target' => '',
		] );

		return $this->factory->get( Link::class, [
			Link::URL        => $cta['url'],
			Link::CONTENT    => $cta['text'] ?: $cta['url'],
			Link::TARGET     => $cta['target'],
			Link::ARIA_LABEL => '', // TODO
			Link::CLASSES    => [ 'card-grid__cta' ],
		] )->render();
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
		return ! empty( $this->attributes['select'][0][ Card_Grid_Block::CARDS ] );
	}

	/**
	 * @return string[]
	 */
	private function get_select_cards(): array {
		return array_map( [ $this, 'build_select_card' ], $this->attributes['select'][0][ Card_Grid_Block::CARDS ] );
	}

	private function build_select_card( array $data ): string {
		// TODO: get a selected post instead of the latest one when we have a working post select field
		$query = new \WP_Query( [ 'post_type' => 'post', 'posts_per_page' => 1 ] );
		$post  = $query->get_posts()[0];

		$title    = empty( $data[ Card_Grid_Block::TITLE_OVERRIDE ] ) ? get_the_title( $post ) : $data[ Card_Grid_Block::TITLE_OVERRIDE ];
		$excerpt  = empty( $data[ Card_Grid_Block::EXCERPT_OVERRIDE ] ) ? get_the_excerpt( $post ) : $data[ Card_Grid_Block::EXCERPT_OVERRIDE ];
		$image_id = empty( $data[ Card_Grid_Block::IMAGE_OVERRIDE ] ) ? get_post_thumbnail_id( $post ) : $data[ Card_Grid_Block::IMAGE_OVERRIDE ];
		$link     = empty( $data[ Card_Grid_Block::LINK_OVERRIDE ] ) ? [ 'url' => get_the_permalink( $post ) ] : $data[ Card_Grid_Block::LINK_OVERRIDE ];

		return $this->factory->get( Card::class, [
			Card::TITLE  => $title,
			Card::TEXT   => $excerpt,
			Card::IMAGE  => $this->render_card_image( $image_id ),
			Card::BUTTON => $this->render_card_link( $link ),
		] )->render();
	}

	/**
	 * @return string[]
	 */
	private function get_query_cards(): array {
		// TODO: build a real query when we have a working posts query field
		$query = new \WP_Query( [ 'post_type' => 'post', 'posts_per_page' => 4 ] );
		return array_map( function ( \WP_Post $post ) {
			return $this->factory->get( Card::class, [
				Card::TITLE  => get_the_title( $post ),
				Card::TEXT   => get_the_excerpt( $post ),
				Card::IMAGE  => $this->render_card_image( get_post_thumbnail_id( $post ) ),
				Card::BUTTON => $this->render_card_link( [ 'url' => get_the_permalink( $post ) ] ),
			] )->render();
		}, $query->get_posts() );
	}

	private function render_card_image( $attachment_id ): string {
		if ( empty( $attachment_id ) ) {
			return '';
		}
		try {
			return $this->factory->get( Image_Component::class, [
				Image_Component::ATTACHMENT   => Image::factory( $attachment_id ),
				Image_Component::SRC_SIZE     => Image_Sizes::COMPONENT_CARD,
				Image_Component::USE_LAZYLOAD => false,
			] )->render();
		} catch ( \InvalidArgumentException $e ) {
			return '';
		}
	}

	private function render_card_link( array $link ): string {
		$link = wp_parse_args( $link, [
			'text'   => __( 'Read more', 'tribe' ),
			'url'    => '',
			'target' => '',
		] );

		return $this->factory->get( Link::class, [
			Link::URL        => $link['url'],
			Link::CONTENT    => $link['text'] ?: $link['url'],
			Link::TARGET     => $link['target'],
			Link::ARIA_LABEL => '', // TODO
			Link::CLASSES    => [ 'card-grid--card__cta' ],
		] )->render();
	}
}
