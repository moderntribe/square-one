<?php


namespace Tribe\Project\Templates;

use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Breadcrumbs;
use Tribe\Project\Templates\Components\Pagination;
use Tribe\Project\Theme\Util;

class Index extends Base {

	public function get_data(): array {
		$data                = parent::get_data();
		$data['posts']       = $this->get_posts();
		$data['page_num']    = $this->get_current_page();
		$data['breadcrumbs'] = $this->get_breadcrumbs();
		$data['pagination']  = $this->get_pagination();

		return $data;
	}

	protected function get_posts() {
		$data = [];
		while ( have_posts() ) {
			the_post();
			$data[] = $this->get_single_post();
		}

		rewind_posts();

		return $data;
	}

	protected function get_single_post() {
		$template = new Content\Loop\Item( $this->template, $this->twig );
		$data    = $template->get_data();

		return $data[ 'post' ];
	}

	protected function get_current_page() {
		return max( 1, get_query_var( 'paged' ) );
	}

	protected function get_breadcrumbs() {

		if ( ! is_archive() ) {
			return '';
		}

		$news_url = get_permalink( get_option( 'page_for_posts' ) );

		$items = [
			[
				'url'   => $news_url,
				'label' => __( 'All News', 'tribe' ),
			],
		];

		$options = [
			Breadcrumbs::ITEMS => $items,
		];

		$crumbs = Breadcrumbs::factory( $options );

		return $crumbs->render();
	}

	public function get_pagination(): string {
		$links = [];

		$numbers = Util::get_pagination_links( 2, true, false, false );

		if ( empty( $numbers ) ) {
			return '';
		}

		foreach ( $numbers as $number ) {

			$number['classes'][] = 'c-pagination__link';

			if ( $number['active'] ) {
				$number['classes'][] = 'active';
			}

			if ( $number['prev'] ) {
				$number['classes'][] = 'icon icon-cal-arrow-left';
			}

			if ( $number['next'] ) {
				$number['classes'][] = 'icon icon-cal-arrow-right';
			}

			$options = [
				Button::CLASSES     => $number['classes'],
				Button::URL         => $number['url'],
				Button::LABEL       => $number['label'],
				Button::BTN_AS_LINK => true,
			];

			$links[] = Button::factory( $options )->render();
		}

		$options = [
			Pagination::LIST_CLASSES       => [ 'g-row', 'g-row--no-gutters', 'c-pagination__list' ],
			Pagination::LIST_ITEM_CLASSES  => [ 'g-col', 'c-pagination__item' ],
			Pagination::WRAPPER_CLASSES    => [ 'c-pagination', 'c-pagination--loop' ],
			Pagination::WRAPPER_ATTRS      => [ 'aria-labelledby' => 'c-pagination__label-single' ],
			Pagination::PAGINATION_NUMBERS => $links,
		];

		return Pagination::factory( $options )->render();
	}
}