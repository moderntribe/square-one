<?php


namespace Tribe\Project\Templates;

class Unsupported_Browser extends Base {

	public function get_data(): array {
		$data                     = parent::get_data();
		$data['post']             = $this->get_post();
		$data['comments']         = $this->get_comments();
		$data['css']              = $this->get_css();
		$data['favicon']          = $this->get_favicon();
		$data['legacy_image_url'] = trailingslashit( get_template_directory_uri() ) . 'img/logos/logo-legacy.png';

		return $data;
	}

	protected function get_post() {
		the_post();
		return [
			'content'        => apply_filters( 'the_content', get_the_content() ),
			'permalink'      => get_the_permalink(),
			'featured_image' => $this->get_featured_image(),
		];
	}

	protected function get_featured_image() {
		$options = [
			'wrapper_class' => 'page__image',
			'echo'          => false,
		];

		return the_tribe_image( get_post_thumbnail_id(), $options );
	}

	protected function get_comments() {
		if ( comments_open() || get_comments_number() > 0 ) {
			ob_start();
			comments_template();

			return ob_get_clean();
		}

		return '';
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