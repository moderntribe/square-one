<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Models;

class Social_Link {
	public $key;
	public $url;
	public $title;

	public function __construct( string $key, string $url, string $title ) {
		$this->key = $key;
		$this->url = $url;
		$this->title = $title;
	}
}
