<?php


namespace Tribe\Project\Admin\Resources;

class JS_Config {

	private $data;

	public function get_data() {
		if ( ! isset( $this->data ) ) {
			$this->data = [
				'images_url' => trailingslashit( get_stylesheet_directory_uri() ) . 'img/admin/',
			];
			$this->data = apply_filters( 'core_admin_js_config', $this->data );
		}

		return $this->data;
	}
}
