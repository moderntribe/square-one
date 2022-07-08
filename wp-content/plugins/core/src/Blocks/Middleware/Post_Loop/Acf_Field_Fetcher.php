<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

class Acf_Field_Fetcher {

	/**
	 * A wrapper for ACF's get_fields() so it can be mocked in tests.
	 *
	 * @param mixed $identifier post_id, block name, term etc...
	 * @param bool  $format_value
	 *
	 * @return array
	 */
	public function get_fields( $identifier = 0, bool $format_value = true ): array {
		return (array) get_fields( $identifier, $format_value );
	}

}
