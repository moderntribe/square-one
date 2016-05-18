<?php


namespace Tribe\Project\Theme;


class Image_Links {
	public function hook() {
		/**
		 * Option: removes default link when inserting uploads
		 */
		add_filter( 'pre_option_image_default_link_type', function() { return 'none'; } );
	}
}