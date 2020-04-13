<?php

namespace Tribe\Project\Templates\Controllers\Page;

use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Controllers\Content;
use Tribe\Project\Templates\Controllers\Document\Document;
use Tribe\Project\Templates\Controllers\Header\Subheader;

class Search extends Index {
	public function __construct(
		Component_Factory $factory,
		Document $document,
		Subheader $header,
		Content\Search_Item $item // Different item template, but otherwise the same as Index
	) {
		parent::__construct( $factory, $document, $header, $item );
	}
}
