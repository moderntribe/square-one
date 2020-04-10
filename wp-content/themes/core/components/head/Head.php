<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components;

class Head extends Context {
	public const LANG       = 'language_attributes';
	public const BLOG_NAME  = 'name';
	public const PINGBACK   = 'pingback_url';
	public const TITLE      = 'page_title';

	protected $path = __DIR__ . '/head.twig';

	protected $properties = [
		self::LANG       => [
			self::DEFAULT => '',
		],
		self::BLOG_NAME  => [
			self::DEFAULT => '',
		],
		self::PINGBACK   => [
			self::DEFAULT => '',
		],
		self::TITLE      => [
			self::DEFAULT => '',
		],
	];
}
