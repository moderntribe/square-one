<?php declare(strict_types=1);

namespace Tribe\Project\Theme\Config;

class Supports {

	/**
	 * @return void
	 *
	 * @action after_setup_theme
	 */
	public function add_theme_supports(): void {
		$this->support_thumbnails();
		$this->support_title_tag();
		$this->support_responsive_embeds();
		$this->support_html5();
		$this->remove_support_block_widgets();
		$this->wp_v6_customizer_fix();
	}

	/**
	 * Supports: enable Featured Images
	 */
	private function support_thumbnails(): void {
		add_theme_support( 'post-thumbnails' );
	}

	/**
	 * Supports: enable Document Title Tag
	 */
	private function support_title_tag(): void {
		add_theme_support( 'title-tag' );
	}

	private function support_responsive_embeds(): void {
		add_theme_support( 'responsive-embeds' );
	}

	/**
	 * Support: switch core WordPress markup to output valid HTML5
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

	/**
	 * Disable Block Editor Widget Support
	 */
	private function remove_support_block_widgets(): void {
		remove_theme_support( 'widgets-block-editor' );
	}

	/**
	 * Prevent notices from being thrown in the customizer on
	 * WordPress version 6.x.
	 */
	private function wp_v6_customizer_fix(): void {
		if ( ! is_customize_preview() || current_theme_supports( 'widgets' ) ) {
			return;
		}

		add_theme_support( 'widgets' );
	}

}
