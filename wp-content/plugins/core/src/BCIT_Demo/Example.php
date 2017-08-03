<?php
namespace Tribe\Project\BCIT_Demo;

class Example {

	/**
	 * Simple function that adds support for Word Documents to allowed file
	 * types allowed to be uploaded via the media manager
	 *
	 * @param array $mime_types
	 *
	 * @return array
	 */
	public function allowed_mime_types( array $mime_types ) {
		$mime_types['doc'] = 'application/msword';

		return $mime_types;
	}
}