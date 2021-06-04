<?php declare(strict_types=1);

namespace Tribe\Project\Query;

use Tribe\Libs\Container\Abstract_Subscriber;

class Query_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_filter( 'posts_request', function ( $request, $wp_query ) {
			return $this->container->get( Search::class )->dont_do_search( (string) $request, $wp_query );
		}, 10, 2 );
	}

}
