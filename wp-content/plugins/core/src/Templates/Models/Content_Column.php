<?php

namespace Tribe\Project\Templates\Models;

class Content_Column {
	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * @var array
	 */
	protected $cta;

	/**
	 * Content_Column constructor.
	 *
	 * @param string $title
	 * @param string $content
	 * @param array  $cta
	 */
	public function __construct( $title, $content, $cta ) {
		$this->title   = $title;
		$this->content = $content;
		$this->cta     = $cta;
	}

	/**
	 * @return string
	 */
	public function get_title(): string {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function get_content(): string {
		return $this->content;
	}

	/**
	 * @return array
	 */
	public function get_cta(): array {
		return $this->cta;
	}


}
