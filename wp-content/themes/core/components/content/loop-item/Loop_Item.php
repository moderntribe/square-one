<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Content;

use Tribe\Project\Templates\Components\Context;

class Loop_Item extends Context {
	public const POST_TYPE = 'post_type';
	public const TITLE     = 'title';
	public const CONTENT   = 'content';
	public const EXCERPT   = 'excerpt';
	public const PERMALINK = 'permalink';
	public const IMAGE     = 'featured_image';
	public const TIMES     = 'time';
	public const AUTHOR    = 'author';

	protected $path = __DIR__ . '/loop-item.twig';

	protected $properties = [
		self::POST_TYPE => [
			self::DEFAULT => '',
		],
		self::TITLE     => [
			self::DEFAULT => '',
		],
		self::CONTENT   => [
			self::DEFAULT => '',
		],
		self::EXCERPT   => [
			self::DEFAULT => '',
		],
		self::PERMALINK => [
			self::DEFAULT => '',
		],
		self::IMAGE     => [
			self::DEFAULT => '',
		],
		self::TIMES     => [
			self::DEFAULT => [
				'c'      => '',
				'F j, Y' => '',
			],
		],
		self::AUTHOR    => [
			self::DEFAULT => [
				'name' => '',
				'url'  => '',
			],
		],
	];
}
