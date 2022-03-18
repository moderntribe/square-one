<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

class Block_Page_Template_Filter {

	/**
	 * A list indexed by page template, containing
	 * the block names assigned to that page template.
	 *
	 * @var array<string, string[]>
	 */
	protected array $block_list;

	public function __construct( array $block_list = [] ) {
		$this->block_list = $block_list;
		$this->prefix_blocks();
	}

	/**
	 * @filter tribe/project/blocks/page_template_filter
	 *
	 * @return array<string, string[]>
	 */
	public function get_block_list(): array {
		return $this->block_list;
	}

	/**
	 * Prefix all block names with "acf/"
	 *
	 * @return void
	 */
	protected function prefix_blocks(): void {
		array_walk_recursive( $this->block_list, static fn ( &$name ) =>
			$name = sprintf( 'acf/%s', $name )
		);
	}

}
