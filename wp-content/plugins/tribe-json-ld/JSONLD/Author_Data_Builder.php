<?php


namespace JSONLD;


class Author_Data_Builder extends Data_Builder {
	protected function build_data() {
		/** @var \WP_User $user */
		$user = $this->object;

		$data = [
			'@context' => 'https://schema.org',
			'@type'    => 'WebPage',
			'name'     => $user->display_name,
			'author'   => [
				'@type' => 'Person',
				'name'  => $user->display_name,
			],
			'url'      => get_author_posts_url( $user->ID ),
		];

		return $data;
	}
}