<?php

namespace Tribe\Project\Templates\Controllers\Pages;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Breadcrumbs;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Context;
use Tribe\Project\Templates\Components\Pages\Index as Index_Context;
use Tribe\Project\Templates\Components\Pages\Page_Wrap;
use Tribe\Project\Templates\Components\Pagination;
use Tribe\Project\Templates\Controllers\Content;
use Tribe\Project\Templates\Controllers\Footer\Footer_Wrap;
use Tribe\Project\Templates\Controllers\Header\Header_Wrap;
use Tribe\Project\Templates\Controllers\Header\Subheader;
use Tribe\Project\Theme\Pagination_Util;
use Twig\Environment;

class Index extends Abstract_Template {
	/**
	 * @var Header_Wrap
	 */
	private $header;
	/**
	 * @var Subheader
	 */
	private $subheader;
	/**
	 * @var Content\Loop_Item
	 */
	private $item;
	/**
	 * @var Footer_Wrap
	 */
	private $footer;

	public function __construct(
		Environment $twig,
		Component_Factory $factory,
		Header_Wrap $header,
		Subheader $subheader,
		Content\Loop_Item $item,
		Footer_Wrap $footer
	) {
		parent::__construct( $twig, $factory );
		$this->header    = $header;
		$this->subheader = $subheader;
		$this->item      = $item;
		$this->footer    = $footer;
	}

	public function render( string $path = '' ): string {
		return $this->factory->get( Page_Wrap::class, [
			Page_Wrap::HEADER => $this->header->render(),
			Page_Wrap::FOOTER => $this->footer->render(),
			Page_Wrap::CONTENT => $this->build_content()->render( $path ),
		])->render();
	}

	protected function build_content(): Context {
		return $this->factory->get( Index_Context::class, [
			Index_Context::SUBHEADER   => $this->subheader->render(),
			Index_Context::POSTS       => $this->render_posts(),
			Index_Context::COMMENTS    => $this->get_comments(),
			Index_Context::BREADCRUMBS => $this->get_breadcrumbs(),
			Index_Context::PAGINATION  => $this->get_pagination(),
		] );
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

			$links[] = $this->factory->get( Button::class, $options )->render();
		}

		return $links;
	}

}
