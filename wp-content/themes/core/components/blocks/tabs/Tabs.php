<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Blocks;

use Tribe\Project\Templates\Components\Context;

class Tabs extends Context {
	public const TABS = 'tabs';

	protected $path = __DIR__ . '/tabs.twig';

	protected $properties = [
		self::TABS => [
			self::DEFAULT => [],
		],
	];
}
