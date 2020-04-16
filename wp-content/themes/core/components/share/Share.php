<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Search
 *
 * @property string[] $links
 */
class Share extends Context {
	public const LINKS = 'links';

	protected $path = __DIR__ . '/share.twig';

	protected $properties = [
		self::LINKS => [
			self::DEFAULT => [],
		],
	];
}
