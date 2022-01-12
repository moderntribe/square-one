<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models;

class Content_Column {

	protected string $title;
	protected string $content;

	/**
	 * @var string[]
	 */
	protected array $cta;

	/**
	 * Content_Column constructor.
	 *
	 * @param string $title
	 * @param string $content
	 * @param array  $cta
	 */
	public function __construct( string $title, string $content, array $cta ) {
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
	 * @return string[]
	 */
	public function get_cta(): array {
		return $this->cta;
	}

}
