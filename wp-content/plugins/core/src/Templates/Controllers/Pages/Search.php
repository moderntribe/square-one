<?php

namespace Tribe\Project\Templates\Controllers\Pages;

use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Controllers\Content;
use Tribe\Project\Templates\Controllers\Footer\Footer_Wrap;
use Tribe\Project\Templates\Controllers\Header\Header_Wrap;
use Tribe\Project\Templates\Controllers\Header\Subheader;
use Twig\Environment;

class Search extends Index {
	public function __construct(
		Environment $twig,
		Component_Factory $factory,
		Header_Wrap $header,
		Subheader $subheader,
		Content\Search_Item $item, // Different item template, but otherwise the same as Index
		Footer_Wrap $footer
	) {
		parent::__construct( $twig, $factory, $header, $subheader, $item, $footer );
	}
}
