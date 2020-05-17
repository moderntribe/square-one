<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Logofarm extends Context {
	public const LOGOS      = 'logos';
	public const ATTRIBUTES = 'attrs';

	protected $path = __DIR__ . '/logofarm.twig';

	protected $properties = [
		self::LOGOS      => [
			self::DEFAULT => [],
		],
		self::ATTRIBUTES => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];
}
