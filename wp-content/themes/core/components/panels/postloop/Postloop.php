<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Postloop extends Context {
	public const POSTS      = 'posts';
	public const ATTRIBUTES = 'attrs';

	protected $path = __DIR__ . '/postloop.twig';

	protected $properties = [
		self::POSTS      => [
			self::DEFAULT => [],
		],
		self::ATTRIBUTES => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];
}
