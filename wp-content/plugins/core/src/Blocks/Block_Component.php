<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

use DI\FactoryInterface;
use Tribe\Project\Blocks\Contracts\Block_Model;

/**
 * @TODO this has a lot of repeat code from the Block_View_Factory, will need clean up.
 */
class Block_Component {

	protected FactoryInterface $container;
	protected string $block_path;

	public function __construct( FactoryInterface $container, string $block_path ) {
		$this->container  = $container;
		$this->block_path = $block_path;
	}

	public function render( string $block_class, Block_Model $model ): void {
		/** @var \Tribe\Libs\ACF\Block_Config $block_config */
		$block_config = $this->container->make( $block_class );
		$attributes   = $block_config->get_block()->get_attributes();

		$controller = $this->container->make( $attributes['controller'], [
			'model' => $model,
		] );

		$file_path = sprintf( $this->block_path, $attributes['view'] );
		$path      = locate_template( $file_path . '.php' );

		if ( ! file_exists( $path ) ) {
			if ( ! WP_DEBUG ) {
				return;
			}
			echo '<pre>';
			print_r( $block_config );
			echo '</pre>';

			return;
		}

		$this->load_template( $path, (array) apply_filters( 'tribe/project/block/view/data', [
			'c'          => $controller,
			'block'      => $block_config,
			'is_preview' => $model->is_preview(),
			'post_id'    => 0,
		], $block_config, $model, $controller ) );
	}

	/**
	 * A better version of load_template.
	 *
	 * @param string $path The path to the view to load.
	 * @param array  $data The data to pass to the view.
	 *
	 * @see \load_template()
	 *
	 * @return void
	 */
	protected function load_template( string $path, array $data = [] ): void {
		global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

		extract( $data, EXTR_SKIP );

		if ( is_array( $wp_query->query_vars ) ) {
			/*
			 * This use of extract() cannot be removed. There are many possible ways that
			 * templates could depend on variables that it creates existing, and no way to
			 * detect and deprecate it.
			 *
			 * Passing the EXTR_SKIP flag is the safest option, ensuring globals and
			 * function variables cannot be overwritten.
			 */
			// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
			extract( $wp_query->query_vars, EXTR_SKIP );
		}

		if ( isset( $s ) ) {
			$s = esc_attr( $s );
		}

		include $path;
	}

}
