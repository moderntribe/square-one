<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Header;

use Tribe\Project\Templates\Components\Context;

class Header_Wrap extends Context {
	public const CONTENT    = 'content';
	public const LANG       = 'language_attributes';
	public const BLOG_NAME  = 'name';
	public const PINGBACK   = 'pingback_url';
	public const TITLE      = 'page_title';
	public const BODY_CLASS = 'body_class';

	protected $path = __DIR__ . '/header-wrap.twig';

	protected $properties = [
		self::CONTENT    => [
			self::DEFAULT => '',
		],
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
		self::BODY_CLASS => [
			self::DEFAULT => '',
		],
	];
}
