<?php declare(strict_types=1);

namespace Tribe\Project\Assets\Admin;

class JS_Config {

	/**
	 * @var string[]
	 */
	private array $data;

	public function get_data(): array {
		if ( ! isset( $this->data ) ) {
			$this->data = [
				'images_url'                 => trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/admin/',
				'block_denylist'             => (array) apply_filters( 'tribe/project/blocks/denylist', [] ),
				'block_page_template_filter' => (array) apply_filters( 'tribe/project/blocks/page_template_filter', [] ),

			];

			$this->data = (array) apply_filters( 'core_admin_js_config', $this->data );
		}

		return $this->data;
	}

}
