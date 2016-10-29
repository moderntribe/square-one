<?php


namespace JSONLD;


class Term_Data_Builder extends Data_Builder {
	protected function build_data() {
		/** @var \WP_Term $term */
		$term = $this->object;

		$data = [
			'@context'    => 'https://schema.org',
			'@type'       => 'WebPage',
			'name'        => $term->name,
			'url'         => get_term_link( $term ),
			'description' => $term->description,
		];

		return $data;
	}
}