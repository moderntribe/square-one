<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Page;

use Tribe\Project\Templates\Components\Breadcrumbs;
use Tribe\Project\Templates\Components\Component;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Pagination;
use Tribe\Project\Theme\Pagination_Util;

class Index extends Component {
	public const SUBHEADER   = 'subheader';
	public const POSTS       = 'posts';
	public const COMMENTS    = 'comments';
	public const BREADCRUMBS = 'breadcrumbs';
	public const PAGINATION  = 'pagination';

	public function init() {
		$this->data[ self::PAGINATION ]  = $this->get_pagination();
		$this->data[ self::BREADCRUMBS ] = $this->get_breadcrumbs();
		$this->data[ self::COMMENTS ]    = $this->get_comments();
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

		return $options;
	}

	public function get_pagination(): array {
		$links = $this->get_pagination_numbers();

		return [
				Pagination::LIST_CLASSES       => [ 'g-row', 'g-row--no-gutters', 'c-pagination__list' ],
				Pagination::LIST_ITEM_CLASSES  => [ 'g-col', 'c-pagination__item' ],
				Pagination::WRAPPER_CLASSES    => [ 'c-pagination', 'c-pagination--loop' ],
				Pagination::WRAPPER_ATTRS      => [ 'aria-labelledby' => 'c-pagination__label-single' ],
				Pagination::PAGINATION_NUMBERS => $links,
		];
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

			$links[] = $options;
		}

		return $links;
	}

	public function render(): void {
		?>
		{% if breadcrumbs %}
			{{ component( 'breadcrumbs/Breadcrumbs.php', breadcrumbs ) }}
		{% endif %}

		{{ component( 'header/subheader/Subheader.php', subheader ) }}

		<div class="l-container">

			{% if posts|length > 0 %}

			{% for post in posts %}
			{{ component( 'content/loop-item/Loop_Item.php', { 'post': post } ) }}
			{% endfor %}

			{{ component( 'pagination/Pagination.php', pagination ) }}

			{% else %}

			{{ component( 'content/no-results/No_Results.php' ) }}

			{% endif %}

		</div>
		<?php
	}
}
