<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

/**
 * Class Deny_Blocks
 *
 * Provides an array of block names to remove from the editor.
 */
class Deny_Blocks {

	/**
	 * @var array A list of blocks types to disable
	 */
	private $denylist;

	public function __construct( array $denylist ) {
		$this->denylist = $denylist;
	}

	/**
	 * Add block types to the deny list to disable in the block editor
	 *
	 * @param string[]
	 *
	 * @return array
	 *
	 * @filter tribe/project/blocks/denylist
	 */
	public function filter_block_denylist( array $types = [] ): array {
		return array_unique( array_merge( $types, $this->denylist ) );
	}

}
