<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Content;

use Tribe\Project\Templates\Components\Context;

class Single extends Context {
	public const POST_TYPE = 'post_type';
	public const TITLE     = 'title';
	public const CONTENT   = 'content';
	public const EXCERPT   = 'excerpt';
	public const PERMALINK = 'permalink';
	public const IMAGE     = 'featured_image';
	public const TIMES     = 'time';
	public const DATE      = 'date';
	public const AUTHOR    = 'author';
	public const SHARE     = 'social_share';

	protected $path = __DIR__ . '/single.twig';

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
				'c' => '',
			],
		],
		self::DATE      => [
			self::DEFAULT => '',
		],
		self::AUTHOR    => [
			self::DEFAULT => [
				'name' => '',
				'url'  => '',
			],
		],
		self::SHARE     => [
			self::DEFAULT => '',
		],
	];
}
