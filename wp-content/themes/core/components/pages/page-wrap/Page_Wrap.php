<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Pages;

use Tribe\Project\Templates\Components\Context;

class Page_Wrap extends Context {
	public const HEADER  = 'header';
	public const CONTENT = 'content';
	public const SIDEBAR = 'sidebar';
	public const FOOTER  = 'footer';

	protected $path = __DIR__ . '/page-wrap.twig';

	protected $properties = [
		self::HEADER  => [
			self::DEFAULT => '',
		],
		self::CONTENT => [
			self::DEFAULT => '',
		],
		self::SIDEBAR => [
			self::DEFAULT => '',
		],
		self::FOOTER  => [
			self::DEFAULT => '',
		],
	];
}
