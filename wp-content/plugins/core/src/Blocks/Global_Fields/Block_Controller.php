<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Global_Fields;

use Tribe\Project\Blocks\Types\Model;

/**
 * Controls which blocks are allowed to have global fields.
 *
 * @package Tribe\Project\Blocks\Global_Field_Meta
 */
class Block_Controller {

	/**
	 * An array of block names that are allowed to have global fields.
	 *
	 * @var string[]
	 */
	protected array $block_names;

	/**
	 * Block_Controller constructor.
	 *
	 * @param array $block_names An array of Block_Config::NAME's
	 */
	public function __construct( array $block_names = [] ) {
		$this->block_names = $block_names;
	}

	/**
	 * Whether the block is in the allowed list.
	 *
	 * @param string $block_name The Block_Config::NAME of the block.
	 *
	 * @return bool
	 */
	public function allowed( string $block_name ): bool {
		$block_name = $this->get_block_name( $block_name );

		return ( in_array( $block_name, $this->block_names, true ) || array_key_exists( $block_name, $this->block_names ) );
	}

	/**
	 * Check if a block only allows specific global field groups.
	 *
	 * @param string     $block_name
	 * @param Meta|Model $instance
	 *
	 * @return bool
	 */
	public function allows_specific_field_group( string $block_name, $instance ): bool {
		$fields = $this->get_allowed_fields( $block_name );

		// Allowed for all
		if ( empty( $fields ) ) {
			return true;
		}

		return in_array( get_class( $instance ), $fields, true );
	}

	/**
	 * A block can specify only certain Meta/Model class names and avoid
	 * all others.
	 *
	 * @param string $block_name
	 *
	 * @return Meta[]|Model[]|array
	 */
	protected function get_allowed_fields( string $block_name ): array {
		$block_name = $this->get_block_name( $block_name );

		if ( ! array_key_exists( $block_name, $this->block_names ) ) {
			return [];
		}

		return (array) $this->block_names[ $block_name ];
	}

	/**
	 * @param string $block_name
	 *
	 * @return string
	 */
	protected function get_block_name( string $block_name ): string {
		return str_replace( 'acf/', '', $block_name );
	}
}
