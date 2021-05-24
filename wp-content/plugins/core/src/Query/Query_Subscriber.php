<?php

namespace Tribe\Project\Query;

use Tribe\Libs\Container\Abstract_Subscriber;

class Query_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_action( 'posts_request', function ( $request, $wp_query ) {
			return $this->container->get( Search::class )->dont_do_search( $request, $wp_query );
		}, 10, 2 );
	}
}
