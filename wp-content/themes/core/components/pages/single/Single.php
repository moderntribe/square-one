<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Pages;

use Tribe\Project\Templates\Components\Context;

class Single extends Context {
	public const SUBHEADER   = 'subheader';
	public const CONTENT     = 'content';
	public const COMMENTS    = 'comments';
	public const BREADCRUMBS = 'breadcrumbs';
	public const PAGINATION  = 'pagination';

	protected $path = __DIR__ . '/single.twig';

	protected $properties = [
		self::SUBHEADER   => [
			self::DEFAULT => '',
		],
		self::CONTENT     => [
			self::DEFAULT => '',
		],
		self::COMMENTS    => [
			self::DEFAULT => '',
		],
		self::BREADCRUMBS => [
			self::DEFAULT => '',
		],
		self::PAGINATION  => [
			self::DEFAULT => '',
		],
	];
}
