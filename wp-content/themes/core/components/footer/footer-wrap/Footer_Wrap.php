<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Footer;

use Tribe\Project\Templates\Components\Context;

class Footer_Wrap extends Context {
	public const CONTENT = 'content';

	protected $path = __DIR__ . '/footer-wrap.twig';

	protected $properties = [
		self::CONTENT => [
			self::DEFAULT => '',
		],
	];
}
