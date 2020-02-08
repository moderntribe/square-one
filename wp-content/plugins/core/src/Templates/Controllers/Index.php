<?php

namespace Tribe\Project\Templates\Controllers;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Breadcrumbs;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Pagination;
use Tribe\Project\Templates\Controllers\Content\Header\Subheader;
use Tribe\Project\Theme\Pagination_Util;
use Twig\Environment;

class Index extends Abstract_Template {
	/**
	 * @var Header
	 */
	private $header;
	/**
	 * @var Subheader
	 */
	private $subheader;
	/**
	 * @var Content\Loop\Item
	 */
	private $item;
	/**
	 * @var Footer
	 */
	private $footer;

	public function __construct(
		string $path,
		Environment $twig,
		Component_Factory $factory,
		Header $header,
		Subheader $subheader,
		Content\Loop\Item $item,
		Footer $footer
	) {
		parent::__construct( $path, $twig, $factory );
		$this->header    = $header;
		$this->subheader = $subheader;
		$this->item      = $item;
		$this->footer    = $footer;
	}


	public function get_data(): array {
		the_post();
		$data = [
			'header'      => $this->header->render(),
			'subheader'   => $this->subheader->render(),
			'posts'       => $this->render_posts(),
			'footer'      => $this->footer->render(),
			'comments'    => $this->get_comments(),
			'breadcrumbs' => $this->get_breadcrumbs(),
			'pagination'  => $this->get_pagination(),
		];

		return $data;
	}

	protected function render_posts(): array {
		$posts = [];
		while ( have_posts() ) {
			the_post();
			$posts[] = $this->item->render();
		}
		rewind_posts();

		return $posts;
	}

	protected function get_comments() {
		if ( comments_open() || get_comments_number() > 0 ) {
			ob_start();
			comments_template();

			return ob_get_clean();
		}

		return '';
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

	public function get_pagination(): string {
		$links = $this->get_pagination_numbers();

		$options = [
			Pagination::LIST_CLASSES       => [ 'g-row', 'g-row--no-gutters', 'c-pagination__list' ],
			Pagination::LIST_ITEM_CLASSES  => [ 'g-col', 'c-pagination__item' ],
			Pagination::WRAPPER_CLASSES    => [ 'c-pagination', 'c-pagination--loop' ],
			Pagination::WRAPPER_ATTRS      => [ 'aria-labelledby' => 'c-pagination__label-single' ],
			Pagination::PAGINATION_NUMBERS => $links,
		];

		return Pagination::factory( $options )->render();
	}

	public function get_pagination_numbers(): array {
		$links = [];

		$pagination = new Pagination_Util();
		$numbers    = $pagination->numbers( 2, true, false, false );

		if ( empty( $numbers ) ) {
			return $links;
		}

		foreach ( $numbers as $number ) {

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

		return $links;
	}

}
