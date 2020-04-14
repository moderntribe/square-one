<?php


namespace Tribe\Project\Templates\Controllers\Page;

use Exception;
use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Breadcrumbs;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Main;
use Tribe\Project\Templates\Components\Page\Page as Page_Context;
use Tribe\Project\Templates\Components\Pagination;
use Tribe\Project\Templates\Controllers\Document\Document;
use Tribe\Project\Templates\Controllers\Header\Subheader;

class Page extends Abstract_Controller {
	/**
	 * @var Subheader
	 */
	private $header;
	/**
	 * @var Document
	 */
	private $document;

	public function __construct(
		Component_Factory $factory,
		Document $document,
		Subheader $header
	) {
		parent::__construct( $factory );
		$this->document = $document;
		$this->header   = $header;
	}

	public function render( string $path = '' ): string {
		the_post();

		return $this->document->render( $this->main( $this->factory->get( Page_Context::class, [
			Page_Context::COMMENTS    => $this->get_comments(),
			Page_Context::BREADCRUMBS => $this->get_breadcrumbs(),
			Page_Context::PAGINATION  => $this->get_pagination(),
			Page_Context::POST        => $this->get_post(),
		] )->render( $path ) ) );
	}

	private function main( string $content ): string {
		return $this->factory->get( Main::class, [
			Main::HEADER  => $this->header->render(),
			Main::CONTENT => $content,
		] )->render();
	}

	protected function get_post() {
		return [
			'content'        => apply_filters( 'the_content', get_the_content() ),
			'permalink'      => get_the_permalink(),
			'featured_image' => $this->get_featured_image(),
		];
	}

	protected function get_featured_image() {
		$image_id = get_post_thumbnail_id();

		if ( empty( $image_id ) ) {
			return '';
		}

		try {
			$image = \Tribe\Project\Templates\Models\Image::factory( $image_id );
		} catch ( Exception $e ) {
			return '';
		}

		$options = [
			Image::ATTACHMENT      => $image,
			Image::WRAPPER_CLASSES => [ 'page__image' ],
		];

		return $this->factory->get( Image::class, $options )->render();
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
			Link::CLASSES => [ 'c-pagination__link', 'anchor', 'pagination__item-anchor' ],
			Link::URL     => get_the_permalink( $post_id ),
			Link::CONTENT => get_the_title( $post_id ),
		];

		$link = $this->factory->get( Link::class, $options );

		return $link->render();
	}

}
