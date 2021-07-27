<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

/**
 * Class Allowed_Blocks
 *
 * Filters the block types that may be inserted in the block editor
 */
class Allowed_Blocks {

	/**
	 * The list of blocks types allowed.
	 *
	 * @var array
	 */
	private array $allow_list;

	/**
	 * Class constructor.
	 *
	 * @param array $allow_list Allowed block types list.
	 */
	public function __construct( array $allow_list = [] ) {
		$this->allow_list = $allow_list;
	}

	/**
	 * Add block types to the allow list to enable in the block editor.
	 *
	 * @param bool|array $allowed_types Currently allowed block types.
	 * @param \WP_Block_Editor_Context   $block_editor_context          The current post object.
	 *
	 * @filter allowed_block_types_all > tribe_allowed_blocks
	 *
	 * @return array                    Modified allowed block types.
	 */
	public function register_allowed_blocks_all( $allowed_types, \WP_Block_Editor_Context $block_editor_context ): array {

		$allowed_types = ! is_array( $allowed_types ) ? [] : $allowed_types;

		return array_unique( array_merge( $allowed_types, $this->allow_list ) );
	}

}
