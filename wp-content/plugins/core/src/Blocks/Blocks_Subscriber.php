<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

use RuntimeException;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Block_Registrar;
use Tribe\Libs\ACF\Block_Renderer;
use Tribe\Libs\Container\Abstract_Subscriber;
use Tribe\Project\Blocks\Global_Field_Meta\Block_Controller;

class Blocks_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_action( 'acf/init', function () {
			foreach ( $this->container->get( Blocks_Definer::TYPES ) as $type ) {
				$this->container->get( Block_Registrar::class )->register( $type );
			}
		}, 10, 1 );

		add_action( 'tribe/project/block/render', function ( ...$args ) {
			$this->container->get( Block_Renderer::class )->render_template( ...$args );
		}, 10, 4 );

		add_filter( 'allowed_block_types', function ( $types, $post ) {
			return $this->container->get( Allowed_Blocks::class )->register_allowed_blocks( $types, $post );
		}, 10, 2 );

		add_action( 'after_setup_theme', function () {
			$this->container->get( Theme_Support::class )->register_theme_supports();

			foreach ( $this->container->get( Blocks_Definer::STYLES ) as $style_override ) {
				/** @var Block_Style_Override $style_override */
				$style_override->register();
			}
		}, 10, 0 );

		$this->register_global_block_fields();
	}

	/**
	 * @throws RuntimeException
	 */
	private function register_global_block_fields(): void {
		add_filter( 'tribe/block/register/fields', function ( $fields, Block_Config $block ): array {

			$controller = $this->container->get( Block_Controller::class );

			// Only blocks specified will have global fields added to them.
			if ( ! $controller->allowed( $block::NAME ) ) {
				return $fields;
			}

			/** @var Global_Field_Meta\Meta\Meta $meta */
			foreach ( $this->container->get( Blocks_Definer::GLOBAL_BLOCK_FIELD_COLLECTION ) as $meta ) {
				if ( ! $meta instanceof Global_Field_Meta\Meta\Meta ) {
					throw new RuntimeException(
						sprintf(
							'%s is not an instance of \Tribe\Project\Blocks\Global_Field_Meta\Meta',
							get_class( $meta )
						)
					);
				}

				$fields = array_merge( $fields, $meta->get_fields() );
			}

			return $fields;
		}, 10, 2 );
	}
}
