<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Sidebar
 */
class Sidebar extends Context {
	public const ACTIVE  = 'active';
	public const CONTENT = 'content';

	protected $path = __DIR__ . '/sidebar.twig';

	protected $properties = [
		self::ACTIVE  => [
			self::DEFAULT => false,
		],
		self::CONTENT => [
			self::DEFAULT => '',
		],
	];
}
