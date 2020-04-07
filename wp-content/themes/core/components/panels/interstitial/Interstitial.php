<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Interstitial extends Context {
	public const IMAGE         = 'image';
	public const LAYOUT        = 'layout';
	public const COLOR         = 'text_color';
	public const CONTENT_BLOCK = 'content_block';

	protected $path = __DIR__ . '/interstitial.twig';

	protected $properties = [
		self::IMAGE         => [
			self::DEFAULT => '',
		],
		self::LAYOUT        => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
		self::COLOR         => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
		self::CONTENT_BLOCK => [
			self::DEFAULT => '',
		],
	];
}
