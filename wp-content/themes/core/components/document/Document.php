<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Document;

use Tribe\Project\Templates\Components\Context;

class Document extends Context {
	public const LANG       = 'language_attributes';
	public const HEAD       = 'head';
	public const BODY_CLASS = 'body_class';
	public const MASTHEAD   = 'masthead';
	public const MAIN       = 'main';
	public const SIDEBAR    = 'sidebar';
	public const FOOTER     = 'footer';

	protected $path = __DIR__ . '/document.twig';

	protected $properties = [
		self::HEAD       => [
			self::DEFAULT => '',
		],
		self::LANG       => [
			self::DEFAULT => '',
		],
		self::BODY_CLASS => [
			self::DEFAULT => '',
		],
		self::MASTHEAD   => [
			self::DEFAULT => '',
		],
		self::MAIN       => [
			self::DEFAULT => '',
		],
		self::SIDEBAR    => [
			self::DEFAULT => '',
		],
		self::FOOTER     => [
			self::DEFAULT => '',
		],
	];
}
