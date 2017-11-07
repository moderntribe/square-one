<?php


namespace Tribe\Project\Templates;

use Tribe\Project\Templates\Components\Breadcrumbs;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Pagination;
use Tribe\Project\Theme\Social_Links;

class Single extends Base {
	public function get_data(): array {
		$data                 = parent::get_data();
		$data['post']         = $this->get_post();
		$data['comments']     = $this->get_comments();
		$data['breadcrumbs']  = $this->get_breadcrumbs();
		$data['pagination']   = $this->get_pagination();
		$data['social_share'] = $this->get_social_share();

		return $data;
	}

	protected function get_post() {
		// can't use get_components because we need to setup global postdata first.
		$template = new Content\Single\Post( $this->template, $this->twig );
		the_post();
		$data = $template->get_data();
		return $data['post'];
	}

	protected function get_comments() {
		if ( comments_open() || get_comments_number() > 0 ) {
			ob_start();
			comments_template();

			return ob_get_clean();
		}

		return '';
	}

	protected function get_social_share() {
		$social = new Social_Links( [ 'facebook', 'twitter', 'google', 'linkedin', 'email' ], false );
		return $social->format_links( $social->get_links() );
	}

	protected function get_breadcrumbs() {
		$news_url = get_permalink( get_option( 'page_for_posts' ) );

		$items = [
			[
				'url'   => $news_url,
				'label' => __( 'News', 'tribe' ),
			],
		];

		$options = [
			Breadcrumbs::ITEMS           => $items,
			Breadcrumbs::WRAPPER_CLASSES => [],
		];

		$crumbs = Breadcrumbs::factory( $options );

		return $crumbs->render();
	}

	protected function get_pagination(): string {
		$get_prev_post = $this->get_pagination_item( get_previous_post() );
		$get_next_post = $this->get_pagination_item( get_next_post() );
		$prev_post     = sprintf( '<span class="pagination__item-label">%s</span> %s', __( 'Previous', 'tribe' ), $get_prev_post );
		$next_post     = sprintf( '<span class="pagination__item-label">%s</span> %s', __( 'Next', 'tribe' ), $get_next_post );

		$options = [
			Pagination::NEXT_POST         => empty( $get_next_post ) ? '' : $next_post,
			Pagination::PREV_POST         => empty( $get_prev_post ) ? '' : $prev_post,
			Pagination::LIST_CLASSES      => [ 'g-row', 'g-row--no-gutters', 'g-row--col-2', 'pagination__list' ],
			Pagination::LIST_ITEM_CLASSES => [ 'g-col', 'pagination__item' ],
			Pagination::WRAPPER_CLASSES   => [ 'pagination', 'pagination--single' ],
			Pagination::WRAPPER_ATTRS     => [ 'aria-labelledby' => 'pagination__label-single' ],
		];

		$pagination = Pagination::factory( $options );

		return $pagination->render();
	}

	protected function get_pagination_item( $post_id ): string {

		if ( empty( $post_id ) ) {
			return '';
		}

		$options = [
			Button::URL         => get_the_permalink( $post_id ),
			Button::LABEL       => get_the_title( $post_id ),
			Button::BTN_AS_LINK => true,
			Button::CLASSES     => [ 'c-pagination__link', 'anchor', 'pagination__item-anchor' ],
		];

		$link = Button::factory( $options );

		return $link->render();
	}

}
