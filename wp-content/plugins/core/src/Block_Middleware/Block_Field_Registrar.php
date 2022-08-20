<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware;

use Tribe\Libs\ACF\Block_Registrar;
use Tribe\Project\Block_Middleware\Contracts\Has_Middleware_Params;
use Tribe\Project\Block_Middleware\Pipelines\Add_Fields_Pipeline;

/**
 * Block Middleware Field Registration.
 */
class Block_Field_Registrar {

	protected Block_Registrar $block_registrar;
	protected Add_Fields_Pipeline $middelware;

	/**
	 * @var \Tribe\Libs\ACF\Block_Config[]
	 */
	protected array $blocks;

	public function __construct( Block_Registrar $block_registrar, Add_Fields_Pipeline $middelware, array $blocks ) {
		$this->block_registrar = $block_registrar;
		$this->middelware      = $middelware;
		$this->blocks          = $blocks;
	}

	/**
	 * Pass blocks through field middleware before registering them with ACF.
	 *
	 * @action acf/init
	 *
	 * @return void
	 */
	public function register(): void {
		foreach ( $this->blocks as $block ) {
			$params = [];

			// Check if this block defines additional middleware parameters.
			if ( $block instanceof Has_Middleware_Params ) {
				$params = $block->get_middleware_params();
			}

			// Pass through all available field middleware pipeline stages and return the modified block.
			$block = $this->middelware->process( $block, $params );

			// Register the modified block with ACF.
			$this->block_registrar->register( $block );
		}
	}

}
