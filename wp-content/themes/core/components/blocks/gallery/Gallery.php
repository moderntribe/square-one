<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Blocks;

use Tribe\Project\Templates\Components\Context;

class Gallery extends Context {
	public const SLIDER = 'slider';

	protected $path = __DIR__ . '/gallery.twig';

	protected $properties = [
		self::SLIDER => [
			self::DEFAULT => '',
		],
	];
}
