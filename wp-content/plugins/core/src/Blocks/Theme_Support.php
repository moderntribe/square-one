<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

use Tribe\Project\Theme\Config\Font_Sizes;

class Theme_Support {

	private Font_Sizes $font_sizes;

	public function __construct( Font_Sizes $font_sizes ) {
		$this->font_sizes = $font_sizes;
	}

	/**
	 * @action after_setup_theme
	 */
	public function register_theme_supports(): void {
		$this->alignment();
		$this->disable_custom_values();
		$this->fonts();
		$this->disable_block_patterns();
	}

	private function alignment(): void {
		add_theme_support( 'align-wide' );
	}

	private function disable_custom_values(): void {
		add_theme_support( 'disable-custom-font-sizes' );
	}

	private function fonts(): void {
		add_theme_support( 'editor-font-sizes', $this->font_sizes->format_for_blocks() );
	}

	private function disable_block_patterns(): void {
		remove_theme_support( 'core-block-patterns' );
	}

}
