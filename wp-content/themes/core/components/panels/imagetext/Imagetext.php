<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Imagetext extends Context {
	public const IMAGE           = 'image';
	public const WRAPPER_CLASSES = 'wrapper_classes';
	public const CONTENT_BLOCK   = 'content_block';

	protected $path = __DIR__ . '/imagetext.twig';

	protected $properties = [
		self::IMAGE           => [
			self::DEFAULT => '',
		],
		self::WRAPPER_CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
		self::CONTENT_BLOCK   => [
			self::DEFAULT => '',
		],
	];
}
