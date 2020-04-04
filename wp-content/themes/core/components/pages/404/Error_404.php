<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Pages;

use Tribe\Project\Templates\Components\Context;

class Error_404 extends Context {
	public const HEADER    = 'header';
	public const SUBHEADER = 'subheader';
	public const FOOTER    = 'footer';
	public const TITLE     = 'error_404_browser_title';
	public const CONTENT   = 'error_404_browser_content';


	protected $path = __DIR__ . '/404.twig';

	protected $properties = [
		self::HEADER    => [
			self::DEFAULT => '',
		],
		self::SUBHEADER => [
			self::DEFAULT => '',
		],
		self::FOOTER    => [
			self::DEFAULT => '',
		],
		self::TITLE     => [
			self::DEFAULT => '',
		],
		self::CONTENT   => [
			self::DEFAULT => '',
		],
	];
}
