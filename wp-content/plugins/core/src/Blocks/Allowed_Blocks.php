<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use Tribe\Libs\ACF\Block;

/**
 * Class Allowed_Blocks
 *
 * Filters the block types that may be inserted in the block editor
 */
class Allowed_Blocks {
	/**
	 * @var array The list of blocks types allowed
	 */
	private array $allow_list;

	public function __construct( array $allow_list ) {
		$this->allow_list = $allow_list;
	}

	/**
	 * Add block types to the allow list to enable in the block editor
	 *
	 * @param bool|array $allowed_types
	 * @param \WP_Post $post
	 *
	 * @return array
	 * @filter allowed_block_types
	 */
	public function register_allowed_blocks( $allowed_types, \WP_Post $post ): array {
		if ( is_bool( $allowed_types ) ) {
			$allowed_types = [];
		}

		return array_unique( array_merge( $allowed_types, $this->allow_list ) );
	}
}
