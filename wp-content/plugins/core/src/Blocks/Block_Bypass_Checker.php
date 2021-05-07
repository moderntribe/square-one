<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

class Block_Bypass_Checker {

	/**
	 * @var string[]
	 */
	private array $block_names;

	public function __construct( array $block_names = [] ) {
		$this->block_names = $block_names;
	}

	public function bypass( string $block_name ): bool {
		return in_array( $block_name, $this->block_names, true );
	}
}
