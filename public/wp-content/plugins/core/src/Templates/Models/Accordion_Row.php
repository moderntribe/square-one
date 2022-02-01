<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models;

class Accordion_Row {

	public string $header_text;

	public string $content;

	/**
	 * Accordion_Row constructor.
	 *
	 * @param string $header_text
	 * @param string $content
	 */
	public function __construct(
		string $header_text,
		string $content
	) {
		$this->header_text = $header_text;
		$this->content     = $content;
	}

}
