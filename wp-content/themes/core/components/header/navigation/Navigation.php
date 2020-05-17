<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Header;

use Tribe\Project\Templates\Components\Context;

class Navigation extends Context {
	public const MENU = 'menu';

	protected $path = __DIR__ . '/navigation.twig';

	protected $properties = [
		self::MENU => [
			self::DEFAULT => '',
		],
	];
}
