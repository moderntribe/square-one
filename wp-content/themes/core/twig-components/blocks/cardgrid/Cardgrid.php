<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Blocks;

use Tribe\Project\Templates\Components\Context;

class Cardgrid extends Context {
	public const CARDS      = 'cards';
	public const ATTRIBUTES = 'attrs';

	protected $path = __DIR__ . '/cardgrid.twig';

	protected $properties = [
		self::CARDS      => [
			self::DEFAULT => [],
		],
		self::ATTRIBUTES => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];
}
