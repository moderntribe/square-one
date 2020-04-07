<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Default_Panel extends Context {
	public const PANEL = 'panel';

	protected $path = __DIR__ . '/default-panel.twig';

	protected $properties = [
		self::PANEL => [
			self::DEFAULT => [], // an array of keys/values for panel vars
		],
	];
}
