<?php declare(strict_types=1);

use Tribe\Project\Blocks\Block_Component;
use Tribe\Project\Blocks\Contracts\Block_Model;
use Tribe\Project\Templates\Components\Deferred_Component;

if ( ! function_exists( 'tribe_template_part' ) ) {
	/**
	 * @param string      $slug
	 * @param string|null $name
	 * @param array       $args
	 *
	 * @return string
	 */
	function tribe_template_part( string $slug, ?string $name = null, array $args = [] ): string {
		ob_start();

		get_template_part( $slug, $name, $args );

		return (string) ob_get_clean();
	}
}

if ( ! function_exists( 'defer_template_part' ) ) {
	/**
	 * @param string      $slug
	 * @param string|null $name
	 * @param array       $args
	 *
	 * @return \Tribe\Project\Templates\Components\Deferred_Component
	 */
	function defer_template_part( string $slug, ?string $name = null, array $args = [] ): Deferred_Component {
		return new Deferred_Component( $slug, $name, $args );
	}
}

if ( ! function_exists( 'tribe_render_block' ) ) {
	/**
	 * Render a block manually by passing in your custom populated model.
	 *
	 * @param string                                      $block_class The FQCN to the block's Block_Config class.
	 * @param \Tribe\Project\Blocks\Contracts\Block_Model $model       The populated model for which you wish to render.
	 *
	 * @throws \Psr\Container\ContainerExceptionInterface
	 * @throws \Psr\Container\NotFoundExceptionInterface
	 */
	function tribe_render_block( string $block_class, Block_Model $model ): void {
		tribe_project()->container()->get( Block_Component::class )->render( $block_class, $model );
	}
}
