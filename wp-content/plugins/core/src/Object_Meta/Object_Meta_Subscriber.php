<?php
declare( strict_types=1 );

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\Object_Meta\Meta_Repository;
use Tribe\Libs\Container\Abstract_Subscriber;

class Object_Meta_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_action( 'init', function () {
			$this->container->get( Meta_Repository::class )->hook();
		} );

		add_action( 'acf/init', function () {
			foreach ( $this->container->get( Object_Meta_Definer::GROUPS ) as $group ) {
				$group->register_group();
			}
		}, 10, 0 );
	}
}
