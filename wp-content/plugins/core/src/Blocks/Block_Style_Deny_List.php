<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

/**
 * Class Block_Style_Deny_List
 *
 * Removes block styles from the block specified as the array key.
 *
 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/
 */
class Block_Style_Deny_List {

	/**
	 * @var array<string, string[]>
	 */
	private array $style_denylist;

	/**
	 * @param array<string, string[]> $style_denylist
	 */
	public function __construct( array $style_denylist = [] ) {
		$this->style_denylist = $style_denylist;
	}

	/**
	 * Adds block type and it styles to the deny list to disable it in the block editor.
	 *
	 * @filter tribe/project/blocks/style_denylist
	 *
	 * @param array<string, string[]> $block_styles
	 *
	 * @return array<string, string[]>
	 */
	public function filter_block_style_denylist( array $block_styles = [] ): array {
		return array_merge( $block_styles, $this->style_denylist );
	}

}
