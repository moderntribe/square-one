<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components;

class Masthead extends Context {
	public const NAVIGATION = 'navigation';
	public const LOGO       = 'logo';
	public const SEARCH     = 'search';

	protected $path = __DIR__ . '/masthead.twig';

	protected $properties = [
		self::NAVIGATION    => [
			self::DEFAULT => '',
		],
		self::LOGO       => [
			self::DEFAULT => '',
		],
		self::SEARCH  => [
			self::DEFAULT => '',
		],
	];
}
