<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Videotext extends Context {
	public const VIDEO           = 'video';
	public const WRAPPER_CLASSES = 'wrapper_classes';
	public const CONTENT_BLOCK   = 'content_block';

	protected $path = __DIR__ . '/videotext.twig';

	protected $properties = [
		self::VIDEO           => [
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
