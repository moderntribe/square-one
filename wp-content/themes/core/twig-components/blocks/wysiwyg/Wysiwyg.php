<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Blocks;

use Tribe\Project\Templates\Components\Context;

class Wysiwyg extends Context {
	public const COLUMNS = 'columns';

	protected $path = __DIR__ . '/wysiwyg.twig';

	protected $properties = [
		self::COLUMNS => [
			self::DEFAULT => [],
		],
	];
}
