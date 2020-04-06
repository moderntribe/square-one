<?php


namespace Tribe\Project\Templates\Controllers\Pages;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Breadcrumbs;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Context;
use Tribe\Project\Templates\Components\Pages\Page_Wrap;
use Tribe\Project\Templates\Components\Pages\Single as Single_Context;
use Tribe\Project\Templates\Components\Pagination;
use Tribe\Project\Templates\Controllers\Content;
use Tribe\Project\Templates\Controllers\Footer\Footer_Wrap;
use Tribe\Project\Templates\Controllers\Header\Header_Wrap;
use Tribe\Project\Templates\Controllers\Header\Subheader;

class Single extends Abstract_Controller {
	/**
	 * @var Header_Wrap
	 */
	private $header;
	/**
	 * @var Subheader
	 */
	private $subheader;
	/**
	 * @var Content\Single
	 */
	private $content;
	/**
	 * @var Footer_Wrap
	 */
	private $footer;

	public function __construct(
		Component_Factory $factory,
		Header_Wrap $header,
		Subheader $subheader,
		Content\Single $content,
		Footer_Wrap $footer
	) {
		parent::__construct( $factory );
		$this->header    = $header;
		$this->subheader = $subheader;
		$this->content   = $content;
		$this->footer    = $footer;
	}

	public function render( string $path = '' ): string {
		the_post();

		return $this->factory->get( Page_Wrap::class, [
			Page_Wrap::HEADER  => $this->header->render(),
			Page_Wrap::FOOTER  => $this->footer->render(),
			Page_Wrap::CONTENT => $this->build_content()->render( $path ),
		] )->render();
	}

	protected function build_content(): Context {
		return $this->factory->get( Single_Context::class, [
			Single_Context::SUBHEADER   => $this->subheader->render(),
			Single_Context::CONTENT     => $this->content->render(),
			Single_Context::COMMENTS    => $this->get_comments(),
			Single_Context::BREADCRUMBS => $this->get_breadcrumbs(),
			Single_Context::PAGINATION  => $this->get_pagination(),
		] );
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

		$crumbs = $this->factory->get( Breadcrumbs::class, $options );

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

		$pagination = $this->factory->get( Pagination::class, $options );

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

		$link = $this->factory->get( Button::class, $options );

		return $link->render();
	}

}
