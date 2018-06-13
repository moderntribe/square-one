<?php


namespace Tribe\Project\Nav;

use Tribe\Project\Utils\Json_Utils;

class JSON_Nav_Walker extends Object_Nav_Walker {
	/**
	 * Translate the menu items to JSON.
	 *
	 * @param array $menu_items
	 * @return string
	 */
	protected function format_output( $menu_items ) {
		$output = parent::format_output($menu_items);
		$json = json_encode($output);
		// in the event of invaiid UTF8 characters, try to clean up and re-encode
		if ( $output && empty( $json ) && json_last_error() === JSON_ERROR_UTF8 ) {
			$cleaned = Json_Utils::utf8_encode_recursive($output);
			$json = json_encode( $cleaned );
		}
		return $json;
	}
} 