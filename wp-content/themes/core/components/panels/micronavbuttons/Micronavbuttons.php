<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Micronavbuttons extends Context {
	public const ITEMS      = 'items';
	public const ATTRIBUTES = 'attrs';

	protected $path = __DIR__ . '/micronavbuttons.twig';

	protected $properties = [
		self::ITEMS      => [
			self::DEFAULT => [],
		],
		self::ATTRIBUTES => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];
}
