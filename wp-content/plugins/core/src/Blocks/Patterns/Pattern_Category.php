<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Patterns;

class Pattern_Category {

	public const CUSTOM_PATTERN_CATEGORY_SLUG = 'tribe-custom';
	private const PATTERN_CATEGORY_LABEL      = 'label';

	public function register_block_category(): void {
		register_block_pattern_category(
			self::CUSTOM_PATTERN_CATEGORY_SLUG,
			[
				self::PATTERN_CATEGORY_LABEL => esc_html__( 'Custom', 'tribe' ),
			]
		);
	}

}
