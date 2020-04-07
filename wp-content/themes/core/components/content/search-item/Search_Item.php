<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Content;

use Tribe\Project\Templates\Components\Context;

class Search_Item extends Context {
	public const POST_TYPE = 'post_type';
	public const TITLE     = 'title';
	public const EXCERPT   = 'excerpt';
	public const PERMALINK = 'permalink';
	public const IMAGE     = 'featured_image';

	protected $path = __DIR__ . '/search-item.twig';

	protected $properties = [
		self::POST_TYPE => [
			self::DEFAULT => '',
		],
		self::TITLE     => [
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
	];
}
