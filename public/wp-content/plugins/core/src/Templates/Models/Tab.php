<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models;

class Tab {

	public string $label;

	public string $content;

	public function __construct( string $label, string $content ) {
		$this->label   = $label;
		$this->content = $content;
	}

}
