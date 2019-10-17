<?php


namespace Tribe\Project\Templates;

class Search extends Index {

	public function get_data(): array {
		$data                   = parent::get_data();
		$data['search_details'] = $this->get_search_details();

		return $data;
	}

	protected function get_single_post() {
		$template = new Content\Search\Search( $this->template, $this->twig );
		$data     = $template->get_data();

		return $data['post'];
	}

	protected function get_search_details(): array {
		global $wp_query;

		if ( ! $wp_query instanceof \WP_Query ) {
			return [];
		}

		$posts_found = $wp_query->found_posts;
		$query       = get_search_query();

		$result_text = _n(
			'result',
			'results',
			$posts_found,
			'tribe'
		);

		$search_details = [
			'query'   => esc_html( $query ),
			'results' => esc_html( $result_text ),
			'count'   => $posts_found,
		];

		return $search_details;
	}
}