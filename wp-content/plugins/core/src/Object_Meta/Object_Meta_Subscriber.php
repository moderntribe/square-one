<?php
declare( strict_types=1 );

namespace Tribe\Project\Object_Meta;

use Psr\Container\ContainerInterface;
use Tribe\Libs\Object_Meta\Meta_Repository;
use Tribe\Libs\Container\Subscriber_Interface;

class Object_Meta_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		add_action( 'init', function () use ( $container ) {
			$container->get( Meta_Repository::class )->hook();
		} );

		add_action( 'acf/init', function () use ( $container ) {
			foreach ( $container->get( 'meta.groups' ) as $group ) {
				$group->register_group();
			}
		}, 10, 0 );
	}
}
