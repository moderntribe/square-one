<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Header;

use Tribe\Project\Templates\Components\Context;

class Subheader extends Context {
	public const TITLE      = 'page_title';

	protected $path = __DIR__ . '/subheader.twig';

	protected $properties = [
		self::TITLE      => [
			self::DEFAULT => '',
		],
	];
}
