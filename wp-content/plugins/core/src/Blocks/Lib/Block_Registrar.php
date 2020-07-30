<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Lib;

class Block_Registrar {
	/**
	 * @param Block_Config $block_config
	 */
	public function register( Block_Config $block_config ) {
		$block_config->init();
		$this->register_block( $block_config );
		$this->register_fields( $block_config );
	}

	/**
	 * @param Block_Config $block_config
	 */
	protected function register_block( Block_Config $block_config ) {
		acf_register_block_type( $block_config->get_block()->get_attributes() );
	}

	/**
	 * @param Block_Config $block_config
	 */
	protected function register_fields( Block_Config $block_config ) {
		acf_add_local_field_group( $block_config->get_field_group()->get_attributes() );
	}


}