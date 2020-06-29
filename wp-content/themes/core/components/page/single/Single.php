<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Page;

use Tribe\Project\Models\Post;
use Tribe\Project\Templates\Components\Breadcrumbs;
use Tribe\Project\Templates\Components\Component;
use Tribe\Project\Templates\Components\Content\Single as Single_Context;
use Tribe\Project\Templates\Components\Context;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Pagination;

class Single extends Component {

	public const SUBHEADER   = 'subheader';
	public const CONTENT     = 'content';
	public const COMMENTS    = 'comments';
	public const BREADCRUMBS = 'breadcrumbs';
	public const PAGINATION  = 'pagination';

	public function init() {
		$this->data[ self::PAGINATION ]  = $this->get_pagination();
		$this->data[ self::BREADCRUMBS ] = $this->get_breadcrumbs();
		$this->data[ self::COMMENTS ]    = $this->get_comments();
	}

	protected function get_pagination() {
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

		return $options;
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

		$link = new Link( $options );

		return $link->get_render();
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

		return $options;
	}

	public function render(): void {
		?>
        {% if breadcrumbs %}
            {{ component( 'breadcrumbs/Breadcrumbs.php', breadcrumbs ) }}
        {% endif %}

        {{ component( 'header/subheader/Subheader.php', subheader ) }}

        <div class="l-container">
            {{ component( 'content/single/Single.php', { 'post': post } ) }}

            {{ component( 'pagination/Pagination.php', pagination ) }}
        </div>

        {{ do_action( 'the_panels') }}

        {{ comments }}
		<?php
	}
}
