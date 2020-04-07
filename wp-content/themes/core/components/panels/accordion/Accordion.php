<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Accordion extends Context {

	public const LAYOUT      = 'layout';
	public const ACCORDION   = 'accordion';
	public const GRID_CASSES = 'grid_classes';
	public const ATTRIBUTES  = 'attrs';

	protected $path = __DIR__ . '/accordion.twig';

	protected $properties = [
		self::LAYOUT      => [
			self::DEFAULT => '',
		],
		self::ACCORDION   => [
			self::DEFAULT => '',
		],
		self::GRID_CASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
		self::ATTRIBUTES  => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];
}
