<?php
declare( strict_types=1 );

namespace Tribe\Project\Object_Meta;

use DI;
use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Settings;

class Object_Meta_Definer implements Definer_Interface {
	public function define(): array {
		return [
			// add our meta groups to the global array
			\Tribe\Libs\Object_Meta\Object_Meta_Definer::GROUPS => DI\add( [
				DI\get( Analytics_Settings::class ),
				DI\get( Social_Settings::class ),
			] ),

			// add analytics settings to the general settings screen
			Analytics_Settings::class                           => static function ( ContainerInterface $container ) {
				return new Analytics_Settings( [
					'settings_pages' => [ $container->get( Settings\General::class )->get_slug() ],
				] );
			},

			// add social settings to the general settings screen
			Social_Settings::class                              => static function ( ContainerInterface $container ) {
				return new Social_Settings( [
					'settings_pages' => [ $container->get( Settings\General::class )->get_slug() ],
				] );
			},
		];
	}
}
