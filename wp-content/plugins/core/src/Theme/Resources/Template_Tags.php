<?php
namespace Tribe\Project\Theme\Resources;

class Template_Tags {

	public function get_assets_url( $path = '' ) {
		$stem = '/core/assets/';
		if( ! empty( $path ) ) {
			$path = trailingslashit($stem . $path );
		} else {
			$path = $stem;
		}

		return plugins_url( $path );
	}
}
