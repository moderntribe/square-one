<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Main;

use Tribe\Project\Templates\Components\Context;

class Main extends Context {
	public const HEADER  = 'header';
	public const CONTENT = 'content';

	protected $path = __DIR__ . '/main.twig';

	protected $properties = [
		self::HEADER  => [
			self::DEFAULT => '',
		],
		self::CONTENT => [
			self::DEFAULT => '',
		],
	];
}
