<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tribe\Libs\ACF\Block_Registrar;
use Tribe\Libs\ACF\Block_Renderer;
use Tribe\Libs\Container\Abstract_Subscriber;
use Tribe\Project\Block_Middleware\Block_Field_Registrar;

class Blocks_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_action( 'acf/init', function (): void {
			try {
				// Pass blocks through field middleware before registering them with ACF.
				$field_registrar = $this->container->get( Block_Field_Registrar::class );
				$field_registrar->register();
			} catch ( NotFoundExceptionInterface | ContainerExceptionInterface $e ) {
				// Block middleware likely disabled in Core.php, just register them.
				foreach ( $this->container->get( Blocks_Definer::TYPES ) as $type ) {
					$this->container->get( Block_Registrar::class )->register( $type );
				}
			}
		}, 10, 1 );

		add_action( 'tribe/project/block/render', function ( ...$args ): void {
			$this->container->get( Block_Renderer::class )->render_template( ...$args );
		}, 10, 4 );

		/**
		 * Adds the deny list to the JS_Config class.
		 */
		add_filter( 'tribe/project/blocks/denylist', function ( array $types ): array {
			return $this->container->get( Block_Deny_List::class )->filter_block_denylist( $types );
		}, 10, 1 );

		/**
		 * Adds the deny list of block styles to the JS_Config class.
		 */
		add_filter( 'tribe/project/blocks/style_denylist', function ( array $types ): array {
			return $this->container->get( Block_Style_Deny_List::class )->filter_block_style_denylist( $types );
		}, 10, 1 );

		add_action( 'after_setup_theme', function (): void {
			$this->container->get( Theme_Support::class )->register_theme_supports();

			foreach ( $this->container->get( Blocks_Definer::STYLES ) as $style_override ) {
				/** @var \Tribe\Project\Blocks\Block_Style_Override $style_override */
				$style_override->register();
			}
		}, 10, 0 );
	}

}
