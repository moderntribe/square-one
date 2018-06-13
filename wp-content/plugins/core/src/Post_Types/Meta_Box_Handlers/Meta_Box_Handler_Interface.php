<?php

namespace Tribe\Project\Post_Types\Meta_Box_Handlers;


use Tribe\Project\Post_Types\Post_Type_Config;

interface Meta_Box_Handler_Interface {

	const INSTANCE_FILTER = 'tribe_libs_meta_box_handler';

	/**
	 * Hooks the meta box handler class to the required filters/actions if needed.
	 * 
	 * @return void
	 */
	public function hook(  );
	
	/**
	 * Registers the meta boxes for a post type.
	 *
	 * @param Post_Type_Config $config
	 */
	public function register_meta_boxes( Post_Type_Config $config );
}