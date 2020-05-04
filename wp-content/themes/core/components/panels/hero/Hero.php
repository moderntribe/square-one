<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Hero extends Context {

	public const CLASSES         = 'classes';
	public const WRAPPER         = 'wrapper';
	public const WRAPPER_CLASSES = 'wrapper_classes';
	public const HEADER_CLASSES  = 'header_classes';


	public const TITLE            = 'title';
	public const CONTENT          = 'content';
	public const IMAGE            = 'image';
	public const LAYOUT           = 'layout';
	public const BACKGROUND_COLOR = 'background_color'; // bake this in for control
	public const COLOR            = 'foreground_color'; // bake this in for control


	protected $path = __DIR__ . '/hero.twig';

	protected $properties = [
		self::CLASSES     => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'panel', 's-wrapper', 'site-panel' ],
		],
		self::WRAPPER_CLASSES     => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'l-container', 'u-vertical-padding' ],
		],
		self::TITLE       => [
			self::DEFAULT => '',
		],
		self::CONTENT     => [
			self::DEFAULT => '',
		],
		self::IMAGE         => [
			self::DEFAULT => '',
		],
		self::LAYOUT_CLASSES        => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'g-row', 'g-row--vertical-center' ],
		],
		self::BACKGROUND_COLOR => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
	];
}
