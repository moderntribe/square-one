<?php
declare( strict_types=1 );

namespace Tribe\Project\Components;

use Tribe\Libs\Container\Abstract_Subscriber;
use Twig\TwigFunction;

class Components_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_filter( 'tribe/libs/twig/funcitons', function( $fs ) {
			$handler = $this->container->get( Handler::class );
			$fs[] = new TwigFunction( 'component', [ $handler, 'render_component' ] );

			return $fs;
		});
	}


}
