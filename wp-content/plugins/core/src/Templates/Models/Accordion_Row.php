<?php

namespace Tribe\Project\Templates\Models;

class Accordion_Row {
	/**
	 * @var string
	 */
	public $header_text;

	/**
	 * @var string
	 */
	public $content;

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
