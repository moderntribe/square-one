<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Panel extends Context {
	public const DEPTH           = 'depth';
	public const TYPE            = 'type';
	public const INDEX           = 'index';
	public const CHILDREN        = 'children';
	public const CLASSES         = 'classes';
	public const WRAPPER         = 'wrapper';
	public const WRAPPER_CLASSES = 'wrapper_classes';
	public const HEADER_CLASSES  = 'header_classes';
	public const TITLE           = 'title';
	public const DESCRIPTION     = 'description';
	public const CONTENT         = 'content';

	protected $path = __DIR__ . '/panel.twig';

	protected $properties = [
		self::DEPTH       => [
			self::DEFAULT => 0,
		],
		self::TYPE        => [
			self::DEFAULT => '',
		],
		self::INDEX       => [
			self::DEFAULT => 0,
		],
		self::CHILDREN    => [
			self::DEFAULT => [],
		],
		self::CLASSES     => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'panel', 's-wrapper', 'site-panel' ],
		],
		self::WRAPPER     => [
			self::DEFAULT => true,
		],
		self::WRAPPER_CLASSES     => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'l-container' ],
		],
		self::HEADER_CLASSES     => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 's-header' ],
		],
		self::TITLE       => [
			self::DEFAULT => '',
		],
		self::DESCRIPTION => [
			self::DEFAULT => '',
		],
		self::CONTENT     => [
			self::DEFAULT => '',
		],
	];
}
