<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Models;

class Tab {
	/**
	 * @var string
	 */
	public $label;
	/**
	 * @var string
	 */
	public $content;

	public function __construct( string $label, string $content ) {
		$this->label   = $label;
		$this->content = $content;
	}
}
