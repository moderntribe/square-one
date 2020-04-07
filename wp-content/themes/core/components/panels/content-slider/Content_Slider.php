<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Content_Slider extends Context {
	public const SLIDER = 'slider';

	protected $path = __DIR__ . '/content-slider.twig';

	protected $properties = [
		self::SLIDER => [
			self::DEFAULT => '',
		],
	];
}
