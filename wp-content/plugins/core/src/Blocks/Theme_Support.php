<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

use Tribe\Libs\Field_Models\Collections\Swatch_Collection;
use Tribe\Project\Theme\Config\Font_Sizes;
use Tribe\Project\Theme\Config\Gradients;

class Theme_Support {

	private Swatch_Collection $swatch_collection;
	private Gradients $gradients;
	private Font_Sizes $font_sizes;

	public function __construct( Swatch_Collection $swatch_collection, Gradients $gradients, Font_Sizes $font_sizes ) {
		$this->swatch_collection = $swatch_collection;
		$this->gradients         = $gradients;
		$this->font_sizes        = $font_sizes;
	}

	/**
	 * @action after_setup_theme
	 */
	public function register_theme_supports(): void {
		$this->alignment();
		$this->disable_custom_values();
		$this->color_palette();
		$this->fonts();
		$this->disable_block_patterns();
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
		add_theme_support( 'editor-color-palette', $this->swatch_collection->format_for_blocks() );
		add_theme_support( 'editor-gradient-presets', $this->get_gradients() );
	}

	private function get_gradients(): array {
		return $this->gradients->format_for_blocks();
	}

	private function fonts(): void {
		add_theme_support( 'editor-font-sizes', $this->font_sizes->format_for_blocks() );
	}

	private function disable_block_patterns(): void {
		remove_theme_support( 'core-block-patterns' );
	}

}
