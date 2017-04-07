<?php


namespace JSONLD;


class Post_Type_Data_Builder extends Data_Builder {
	protected function build_data() {
		$post_type = $this->object;

		$data = [
			'@context' => 'https://schema.org',
			'@type'    => 'WebPage',
			'name'     => $post_type->label,
			'url'      => get_post_type_archive_link( $post_type->name ),
		];

		return $data;
	}
}