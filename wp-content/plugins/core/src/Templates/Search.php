<?php


namespace Tribe\Project\Templates;

class Search extends Index {
	protected function get_single_post() {
		$template = new Content\Search\Search( $this->template, $this->twig );
		$data    = $template->get_data();

		return $data[ 'post' ];
	}
}
