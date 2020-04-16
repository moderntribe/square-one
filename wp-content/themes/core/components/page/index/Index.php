<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Page;

use Tribe\Project\Templates\Components\Context;

class Index extends Context {
	public const SUBHEADER   = 'subheader';
	public const POSTS       = 'posts';
	public const COMMENTS    = 'comments';
	public const BREADCRUMBS = 'breadcrumbs';
	public const PAGINATION  = 'pagination';

	protected $path = __DIR__ . '/index.twig';

	protected $properties = [
		self::SUBHEADER   => [
			self::DEFAULT => '',
		],
		self::POSTS       => [
			self::DEFAULT => [],
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
