<?php
declare( strict_types=1 );

namespace Tribe\Project\Integrations\Yoast_SEO;

use Tribe\Libs\Container\Abstract_Subscriber;

class Yoast_SEO_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_filter( 'wpseo_opengraph_image_size', function ( $size ) {
			return $this->container->get( Open_Graph::class )->customize_wpseo_image_size( $size );
		}, 10, 1 );
	}

}
