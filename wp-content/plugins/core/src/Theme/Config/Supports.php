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
		$this->support_responsive_embeds();
		$this->support_html5();
	}

	/**
	 * 	 * Supports: enable Featured Images
	 *
	 * @return void
	 */
	private function support_thumbnails(): void {
		add_theme_support( 'post-thumbnails' );
	}

	/**
	 * 	 * Supports: enable Document Title Tag
	 *
	 * @return void
	 */
	private function support_title_tag(): void {
		add_theme_support( 'title-tag' );
	}

	private function support_responsive_embeds(): void {
		add_theme_support( 'responsive-embeds' );
	}

	/**
	 * 	 * Support: switch core WordPress markup to output valid HTML5
	 *
	 * @return void
	 */
	private function support_html5(): void {
		add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		] );
	}
}
