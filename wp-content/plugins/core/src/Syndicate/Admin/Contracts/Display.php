<?php

namespace Tribe\Project\Syndicate\Admin\Contracts;

abstract class Display {

	protected $blog;

	public function __construct( $blog ) {
		$this->blog = $blog;
	}

	protected function blog_id() {
		return get_current_blog_id();
	}

	protected function object_in_blog( $object_id ) {
		$object_id_mapped_to_blog = (int) floor( $object_id / $this->blog::OFFSET );
		$blog_id                  = (int) $this->blog_id();

		return $object_id_mapped_to_blog === $blog_id;
	}

	protected function excluded_meta( $key ) {
		$excluded = [
			'_edit_lock',
			'_edit_last',
		];

		return in_array( $key, $excluded );
	}

}