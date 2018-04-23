<?php


namespace Tribe\Project\Templates;

class Error_404 extends Base {

	public function get_data(): array {
		$data                              = parent::get_data();
		$data['post']                      = $this->get_post();
		$data['error_404_browser_title']   = $this->get_404_page_title();
		$data['error_404_browser_content'] = $this->get_404_page_content();

		return $data;
	}

	protected function get_post() {
		return [
			'title' => __( 'Page Not Found', 'tribe' ),
		];
	}

	protected function get_404_page_title() {
		return __( 'Whoops! We are having trouble locating your page!', 'tribe' );
	}

	protected function get_404_page_content() {
		return __( 'If you\'re lost or have reached this page in error, our apologies. Please use the navigation menu or the links in the footer to find your way through the site. Please e-mail us if you have any questions.', 'tribe' );
	}
}