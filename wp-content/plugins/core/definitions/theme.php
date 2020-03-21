<?php
declare( strict_types=1 );

use Tribe\Project\Theme\Oembed_Filter;
use Tribe\Project\Theme\Resources\Fonts;

return [
	Oembed_Filter::class => DI\autowire()
		->constructorParameter( 'supported_providers', [
			Oembed_Filter::PROVIDER_VIMEO,
			Oembed_Filter::PROVIDER_YOUTUBE,
		] ),
	Fonts::class         => DI\create()
		->constructor( DI\get( 'plugin.file' ), [
			'typekit' => '', // typekit ID
			'google'  => [],
			'custom'  => [],
		] ),
];
