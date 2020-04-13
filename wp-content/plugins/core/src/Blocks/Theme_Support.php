<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use Tribe\Project\Theme\Config\Colors;
use Tribe\Project\Theme\Config\Font_Sizes;
use Tribe\Project\Theme\Config\Gradients;

class Theme_Support {

	/**
	 * @var Colors
	 */
	private $colors;
	/**
	 * @var Gradients
	 */
	private $gradients;
	/**
	 * @var Font_Sizes
	 */
	private $font_sizes;

	public function __construct( Colors $colors, Gradients $gradients, Font_Sizes $font_sizes ) {
		$this->colors     = $colors;
		$this->gradients  = $gradients;
		$this->font_sizes = $font_sizes;
	}

	/**
	 * @return void
	 * @action after_setup_theme
	 */
	public function register_theme_supports(): void {
		$this->alignment();
		$this->disable_custom_values();
		$this->color_palette();
		$this->fonts();
	}

	private function alignment(): void {
		add_theme_support( 'align-wide' );
	}

	private function disable_custom_values(): void {
		add_theme_support( 'disable-custom-colors' );
		add_theme_support( 'disable-custom-gradients' );
		add_theme_support( 'disable-custom-font-sizes' );
	}

	private function color_palette(): void {
		add_theme_support( 'editor-color-palette', $this->get_colors() );
		add_theme_support( 'editor-gradient-presets', $this->get_gradients() );
	}

	private function get_colors(): array {
		return $this->colors->format_for_blocks();
	}

	private function get_gradients(): array {
		return $this->gradients->format_for_blocks();
	}

	private function fonts(): void {
		add_theme_support( 'editor-font-sizes', $this->font_sizes->format_for_blocks() );
	}
}
