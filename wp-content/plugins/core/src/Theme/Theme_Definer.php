<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Theme\Resources\Fonts;

class Theme_Definer implements Definer_Interface {
	public function define(): array {
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
	}

}
