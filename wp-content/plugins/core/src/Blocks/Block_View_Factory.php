<?php declare(strict_types=1);

namespace Tribe\Project\Blocks;

use DI\FactoryInterface;
use WP_Block;

/**
 * @TODO make an interface for this.
 * @TODO move to tribe-libs.
 */
class Block_View_Factory {

	public string $block_path;

	private FactoryInterface $container;

	public function __construct( FactoryInterface $container, string $block_path ) {
		$this->container  = $container;
		$this->block_path = $block_path;
	}

	/**
	 * Build the block model, controller and render the block view.
	 *
	 * @filter tribe/project/block/render
	 *
	 * @param array          $block
	 * @param string         $content
	 * @param bool           $is_preview
	 * @param int            $post_id
	 * @param \WP_Block|null $WP_block
	 * @param array          $context
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 */
	public function render_template( array $block, string $content, bool $is_preview, int $post_id, ?WP_Block $WP_block, array $context = [] ): void {
		$fields = $WP_block ? ( $WP_block->parsed_block['attrs'] ?? [] ) : [];
		$fields = array_merge( $fields, (array) get_fields() );

		$model = $this->container->make( $block['model'], [
			'fields' => $fields,
		] );

		$controller = $this->container->make( $block['controller'], [
			'model' => $model,
		] );

		$file_path = sprintf( $this->block_path, $block['view'] );
		$path      = locate_template( $file_path . '.php' );

		if ( ! file_exists( $path ) ) {
			if ( ! WP_DEBUG ) {
				return;
			}
			echo '<pre>';
			print_r( $block );
			print_r( $content );
			print_r( $is_preview );
			print_r( $post_id );
			echo '</pre>';

			return;
		}

		$this->load_template( $path, (array) apply_filters( 'tribe/project/block/view/data', [
			'c'          => $controller,
			'block'      => $block,
			'is_preview' => $is_preview,
			'post_id'    => $post_id,
			'context'    => $context,
		], $block, $model, $controller ) );
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
