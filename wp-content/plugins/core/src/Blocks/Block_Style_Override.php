<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

class Block_Style_Override {

	/**
	 * @var string[] The block types affected by this style override.
	 */
	protected array $block_types = [];

	/**
	 * @var string[][] The styles to register.
	 *
	 * @link https://developer.wordpress.org/block-editor/developers/filters/block-filters/#register_block_style
	 */
	protected array $register = [];

	/**
	 * @var string[][] The styles to unregister.
	 */
	protected array $unregister = [];

	/**
	 * Block_Style_Override constructor.
	 *
	 * @param string[]   $block_types
	 * @param string[][] $register
	 * @param string[][] $unregister
	 */
	public function __construct( array $block_types, array $register = [], array $unregister = [] ) {
		$this->block_types = $block_types;
		$this->register    = $register;
		$this->unregister  = $unregister;
	}

	/**
	 * @action after_setup_theme
	 */
	public function register(): void {
		foreach ( $this->block_types as $type ) {
			foreach ( $this->register as $style ) {
				register_block_style( $type, $style );
			}

			foreach ( $this->unregister as $handle ) {
				unregister_block_style( $type, $handle );
			}
		}
	}

}
