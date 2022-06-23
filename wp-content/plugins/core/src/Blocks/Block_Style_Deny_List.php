<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

/**
 * Class Block_Style_Deny_List
 *
 * Provides an array of block names together with styles to remove from the editor.
 */
class Block_Style_Deny_List {

	/**
	 * @var array<string, string[]> An array of default block type as key and an array of it styles to unregister.
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
	 * @param array<string, string[]> $block_styles
	 *
	 * @return array<string, string[]>
	 *
	 * @filter tribe/project/blocks/style_denylist
	 */
	public function filter_block_style_denylist( array $block_styles = [] ): array {
		return array_merge( $block_styles, $this->style_denylist );
	}

}
