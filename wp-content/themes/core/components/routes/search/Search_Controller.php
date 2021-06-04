<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\routes\search;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\breadcrumbs\Breadcrumbs_Controller;
use Tribe\Project\Templates\Components\card\Card_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\search_form\Search_Form_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Theme\Config\Image_Sizes;

class Search_Controller extends Abstract_Controller {

	/**
	 * @var int|string
	 */
	private $sidebar_id = '';

	/**
	 * Render the header component
	 *
	 * Bypasses the get_header() function to
	 * load our component instead of header.php
	 *
	 * @return void
	 */
	public function render_header(): void {
		do_action( 'get_header', null );
		get_template_part( 'components/document/header/header', 'index' );
	}


	/**
	 * Render the sidebar component
	 *
	 * Bypasses the get_sidebar() function to
	 * load our component instead of sidebar.php
	 *
	 * @return void
	 */
	public function render_sidebar(): void {
		do_action( 'get_sidebar', null );
		get_template_part(
			'components/sidebar/sidebar',
			'index',
			[ Sidebar_Controller::SIDEBAR_ID => $this->sidebar_id ]
		);
	}

	/**
	 * Render the footer component
	 *
	 * Bypasses the get_footer() function to
	 * load our component instead of footer.php
	 *
	 * @return void
	 */
	public function render_footer(): void {
		do_action( 'get_footer', null );
		get_template_part( 'components/document/footer/footer', 'index' );
	}

	public function render_breadcrumbs(): void {
		//TODO: let's make this get_breadcrumb_args() and render in template
		get_template_part(
			'components/breadcrumbs/breadcrumbs',
			'index',
			[ Breadcrumbs_Controller::BREADCRUMBS => $this->get_breadcrumbs() ]
		);
	}

	/**
	 * @return array
	 */
	public function get_search_form_args(): array {
		$args = [
			Search_Form_Controller::CLASSES => [ 'c-search--full', 'search-results__form' ],
			Search_Form_Controller::FORM_ID => uniqid( 's-' ),
			Search_Form_Controller::VALUE   => get_search_query(),
		];

		return $args;
	}

	/**
	 * @return array
	 */
	public function get_results_text_args(): array {
		global $wp_query;
		$total      = absint( $wp_query->found_posts );
		$query_term = get_search_query();

		if ( empty( $query_term ) ) {
			return [];
		}

		$text = sprintf(
			_n( 'Showing %d result for &lsquo;%s&rsquo;', 'Showing %d results for &lsquo;%s&rsquo;', $total, 'tribe' ),
			$total,
			$query_term
		);

		if ( 0 === $total ) {
			$text = sprintf(
				__( 'Your search for &lsquo;%s&rsquo; returned 0 results', 'tribe' ),
				$query_term
			);
		}

		return [
			Text_Controller::TAG     => 'p',
			Text_Controller::CLASSES => [ 't-display-xx-small', 'search-results__summary' ],
			Text_Controller::CONTENT => esc_html( $text ),
		];
	}

	/**
	 * @return ?\Tribe\Project\Templates\Components\Deferred_Component
	 */
	protected function get_card_image(): ?Deferred_Component {
		if ( empty( get_post_thumbnail_id() ) ) {
			return null;
		}

		return defer_template_part(
			'components/image/image',
			null,
			[
				Image_Controller::IMG_ID       => get_post_thumbnail_id(),
				Image_Controller::SRC_SIZE     => Image_Sizes::FOUR_THREE,
				Image_Controller::SRCSET_SIZES => [
					Image_Sizes::FOUR_THREE_SMALL,
					Image_Sizes::FOUR_THREE,
					Image_Sizes::FOUR_THREE_LARGE,
				],
			],
		);
	}

	/**
	 * @return array
	 */
	public function get_card_args(): array {
		$uuid      = uniqid( 'p-' );
		$card_args = [
			Card_Controller::STYLE           => Card_Controller::STYLE_SEARCH,
			Card_Controller::USE_TARGET_LINK => true,
			Card_Controller::TAG             => 'article',
			Card_Controller::CLASSES         => [
				has_post_thumbnail() ? 'c-card--has-image' : '',
			],
			Card_Controller::IMAGE           => $this->get_card_image(),
			Card_Controller::TITLE           => defer_template_part(
				'components/text/text',
				null,
				[
					Text_Controller::CONTENT => get_the_title(),
					Text_Controller::TAG     => 'h2',
					Text_Controller::ATTRS   => [ 'id' => $uuid . '-title' ],
				]
			),
			Card_Controller::DESCRIPTION     => defer_template_part(
				'components/text/text',
				null,
				[
					Text_Controller::CONTENT => wp_trim_words( get_the_excerpt(), 30, '&hellip;' ),
					Text_Controller::CLASSES => [ 't-body-small' ],
				]
			),
			Card_Controller::CTA             => defer_template_part(
				'components/link/link',
				null,
				[
					Link_Controller::URL     => get_permalink(),
					Link_Controller::CONTENT => get_permalink(),
					Link_Controller::CLASSES => [ 't-helper' ],
					Link_Controller::ATTRS   => [
						'id'               => $uuid . '-link',
						'aria-labelledby'  => $uuid . '-title',
						'aria-describedby' => $uuid . '-link',
						'data-js'          => 'target-link',
					],
				]
			),
		];

		return $card_args;
	}

}
