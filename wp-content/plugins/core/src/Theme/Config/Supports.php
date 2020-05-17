<?php


namespace Tribe\Project\Theme\Config;


class Supports {

	/**
	 * @return void
	 * @action after_setup_theme
	 */
	public function add_theme_supports() {
		$this->support_thumbnails();
		$this->support_title_tag();
		$this->support_html5();
	}

	/**
	 * Supports: enable Featured Images
	 */
	private function support_thumbnails() {
		add_theme_support( 'post-thumbnails' );
	}

	/**
	 * Supports: enable Document Title Tag
	 */
	private function support_title_tag() {
		add_theme_support( 'title-tag' );
	}

	/**
	 * Support: switch core WordPress markup to output valid HTML5
	 */
	private function support_html5() {
		add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		] );
	}
}
