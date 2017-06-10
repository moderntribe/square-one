<?php


namespace Tribe\Project\Templates;

class Unsupported_Browser extends Base {

	public function get_data(): array {
		$data                     = parent::get_data();
		$data['post']             = $this->get_post();
		$data['css']              = $this->get_css();
		$data['favicon']          = $this->get_favicon();
		$data['legacy_image_url'] = trailingslashit( get_template_directory_uri() ) . 'img/logos/logo-legacy.png';

		return $data;
	}

	protected function get_post() {
		the_post();
		return [
			'content'        => apply_filters( 'the_content', get_the_content() ),
		];
	}

	protected function get_favicon() {
		return trailingslashit( get_template_directory_uri() ) . 'img/branding/favicon.ico';
	}

	protected function get_css() {
		$css_dir    = trailingslashit( get_template_directory_uri() ) . 'css/';
		$css_legacy = ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) ? 'dist/legacy.min.css' : 'legacy.css';

		return $css_dir . $css_legacy;
	}
}