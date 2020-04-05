<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Testimonial extends Context {
	public const IMAGE  = 'image';
	public const SLIDER = 'slider';

	protected $path = __DIR__ . '/testimonial.twig';

	protected $properties = [
		self::IMAGE  => [
			self::DEFAULT => '',
		],
		self::SLIDER => [
			self::DEFAULT => '',
		],
	];
}
