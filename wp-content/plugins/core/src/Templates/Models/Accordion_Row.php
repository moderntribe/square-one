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
	public $header_id;

	/**
	 * @var string
	 */
	public $content_id;

	/**
	 * @var string
	 */
	public $content;

	/**
	 * Accordion_Row constructor.
	 *
	 * @param string $header_text
	 * @param string $content
	 * @param string $header_id
	 * @param string $content_id
	 */
	public function __construct(
		string $header_text,
		string $content,
		string $header_id,
		string $content_id
	) {
		$this->header_text = $header_text;
		$this->content     = $content;
		$this->header_id   = $header_id;
		$this->content_id  = $content_id;
	}
}