<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models;

class Breadcrumb {

	public string $url;

	public string $label;

	public function __construct( string $url, string $label ) {
		$this->url   = $url;
		$this->label = $label;
	}

}
