<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Models;

class Breadcrumb {
	/**
	 * @var string
	 */
	public $url;
	/**
	 * @var string
	 */
	public $label;

	public function __construct( string $url, string $label ) {
		$this->url = $url;
		$this->label = $label;
	}
}
