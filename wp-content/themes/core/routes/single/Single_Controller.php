<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Routes\single;

use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\footer\single_footer\Single_Footer_Controller;
use Tribe\Project\Templates\Components\header\subheader\Subheader_Single_Controller;
use Tribe\Project\Templates\Components\Traits\Page_Title;

class Single_Controller extends Abstract_Controller {

	use Page_Title;

	/**
	 * @var int|string
	 */
	public $sidebar_id = '';

	public function get_subheader_args(): array {
		global $post;

		$term = $this->get_first_taxonomy_term();

		return [
			Subheader_Single_Controller::TITLE                => $this->get_page_title(),
			Subheader_Single_Controller::DATE                 => get_the_date(),
			Subheader_Single_Controller::AUTHOR               => get_the_author_meta( 'display_name', $post->post_author ),
			Subheader_Single_Controller::SHOULD_RENDER_BYLINE => true,
			Subheader_Single_Controller::TAG_NAME             => $term->name,
			Subheader_Single_Controller::TAG_LINK             => get_term_link( $term ),
		];
	}

	public function get_content_footer_args(): array {
		return [
			Single_Footer_Controller::CLASSES => [],
		];
	}

	public function get_first_taxonomy_term(): ?\WP_Term {
		$terms = $this->get_taxonomy_terms();

		if ( empty( $terms ) ) {
			return null;
		}

		if ( $terms[0] instanceof \WP_Term ) {
			return $terms[0];
		}

		return null;
	}

	public function get_taxonomy_terms(): array {
		global $post;

		$terms = get_the_terms( $post->ID, Category::NAME );
		if ( ! $terms ) {
			return [];
		}

		return $terms;
	}

}
