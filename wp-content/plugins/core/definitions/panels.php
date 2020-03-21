<?php
declare( strict_types=1 );

use Tribe\Project\Panels;

return [
	'panels.types'            => [
		Panels\Types\Hero::class,
		Panels\Types\Accordion::class,
		Panels\Types\CardGrid::class,
		Panels\Types\Gallery::class,
		Panels\Types\ImageText::class,
		Panels\Types\VideoText::class,
		Panels\Types\Interstitial::class,
		Panels\Types\MicroNavButtons::class,
		Panels\Types\Wysiwyg::class,
		Panels\Types\ContentSlider::class,
		Panels\Types\LogoFarm::class,
		Panels\Types\Testimonial::class,
		Panels\Types\PostLoop::class,
		Panels\Types\Tabs::class,
	],

	Panels\Initializer::class => DI\create()
		->constructor( DI\get( 'plugin.file' ) ),
];
