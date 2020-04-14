<?php

namespace Tribe\Project\Templates\Controllers\Page;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Breadcrumbs;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Main;
use Tribe\Project\Templates\Components\Page\Index as Index_Context;
use Tribe\Project\Templates\Components\Pagination;
use Tribe\Project\Templates\Controllers\Content;
use Tribe\Project\Templates\Controllers\Document\Document;
use Tribe\Project\Templates\Controllers\Header\Subheader;
use Tribe\Project\Theme\Pagination_Util;

class Index extends Abstract_Controller {
	/**
	 * @var Document
	 */
	private $document;

	/**
	 * @var Subheader
	 */
	private $header;
	/**
	 * @var Content\Loop_Item
	 */
	private $item;

	public function __construct(
		Component_Factory $factory,
		Document $document,
		Subheader $header,
		Content\Loop_Item $item
	) {
		parent::__construct( $factory );
		$this->document = $document;
		$this->header   = $header;
		$this->item     = $item;
	}

	public function render( string $path = '' ): string {
		return $this->document->render( $this->main( $this->factory->get( Index_Context::class, [
			Index_Context::COMMENTS    => $this->get_comments(),
			Index_Context::BREADCRUMBS => $this->get_breadcrumbs(),
			Index_Context::PAGINATION  => $this->get_pagination(),
			Index_Context::POSTS       => $this->render_posts(),
		] )->render( $path ) ) );
	}

	private function main( string $content ): string {
		return $this->factory->get( Main::class, [
			Main::HEADER  => $this->header->render(),
			Main::CONTENT => $content,
		] )->render();
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
		if ( have_posts() && ( comments_open() || get_comments_number() > 0 ) ) {
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

		$crumbs = $this->factory->get( Breadcrumbs::class, $options );

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

		return $this->factory->get( Pagination::class, $options )->render();
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
				$number['classes'][] = 'icon';
				$number['classes'][] = 'icon-cal-arrow-left';
			}

			if ( $number['next'] ) {
				$number['classes'][] = 'icon';
				$number['classes'][] = 'icon-cal-arrow-right';
			}

			$options = [
				LINK::CLASSES => $number['classes'],
				LINK::URL     => $number['url'],
				LINK::CONTENT => $number['label'],
			];

			$links[] = $this->factory->get( Link::class, $options )->render();
		}

		return $links;
	}

}
