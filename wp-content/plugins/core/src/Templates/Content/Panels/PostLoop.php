<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\PostLoop as PostLoopPanel;
use Tribe\Project\Templates\Components\Card;

class PostLoop extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'       => $this->get_title( PostLoopPanel::FIELD_TITLE, [ 'site-section__title', 'h2' ] ),
			'description' => ! empty( $this->panel_vars[ PostLoopPanel::FIELD_DESCRIPTION ] ) ? $this->panel_vars[ PostLoopPanel::FIELD_DESCRIPTION ] : false,
			'posts'       => $this->get_the_posts(),
		];

		return $data;
	}

	protected function get_the_posts(): array {
		$posts = [];

		if ( ! empty( $this->panel_vars[ PostLoopPanel::FIELD_POSTS ] ) ) {
			for ( $i = 0; $i < count( $this->panel_vars[ PostLoopPanel::FIELD_POSTS ] ); $i ++ ) {

				$post = $this->panel_vars[ PostLoopPanel::FIELD_POSTS ][ $i ];
				$title = sprintf( '<a href="%s">%s</a>', esc_url( get_the_permalink( $post['post_id'] ) ), esc_html( get_the_title( $post['post_id'] ) ) );

				$options = [
					Card::CLASSES         => [ 'c-card--postlist' ],
					Card::CONTENT_CLASSES => [ 't-content', 'c-card__content--postlist' ],
					Card::TITLE           => $title,
					Card::TITLE_CLASSES   => [ 'c-card__title--postlist' ],
					Card::IMAGE           => esc_html( get_post_thumbnail_id( $post['post_id'] ) ),
					Card::PRE_TITLE       => get_the_category_list( '', '', $post['post_id'] ),
				];

				$post_obj = Card::factory( $options );
				$posts[]  = $post_obj->render();
			}
		}

		return $posts;
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/postloop'];
	}
}
