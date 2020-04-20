<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

/**
 * Class Allowed_Blocks
 *
 * Filters the block types that may be inserted in the block editor
 */
class Allowed_Blocks {
	/**
	 * @var array A list of blocks types to disable
	 */
	private $blacklist;

	public function __construct( array $blacklist ) {
		$this->blacklist = $blacklist;
	}

	/**
	 * Add block types to the blacklist to disable in the block editor
	 *
	 * @param string[]
	 *
	 * @return array
	 * @filter tribe/project/blocks/blacklist
	 */
	public function filter_block_blacklist( array $types ): array {
		return array_unique( array_merge( $types, $this->blacklist ) );
	}
}
