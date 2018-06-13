<?php

namespace Tribe\Project\Post_Types\Meta_Box_Handlers;


use Tribe\Project\Post_Types\Post_Type_Config;

class ACF implements Meta_Box_Handler_Interface {

	/**
	 * Registers the meta boxes for a post type.
	 *
	 * @param Post_Type_Config $config
	 */
	public function register_meta_boxes( Post_Type_Config $config ) {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group( $config->get_meta_boxes() );
	}

	/**
	 * Hooks the meta box handler class to the required filters/actions if needed.
	 *
	 * @return void
	 */
	public function hook() {
		add_filter( Meta_Box_Handler_Interface::INSTANCE_FILTER, function () {
			return $this;
		} );
	}
}