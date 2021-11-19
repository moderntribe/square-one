<?php declare(strict_types=1);

namespace Tribe\Project\Query;

use Tribe\Libs\Container\Abstract_Subscriber;

class Query_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_filter( 'posts_request', function ( $request, $wp_query ) {
			return $this->container->get( Search::class )->force_load_search_template( (string) $request, $wp_query );
		}, 10, 2 );
		add_action( 'pre_get_posts', function ( $query ) {
			return $this->container->get( Featured_Posts::class )->remove_featured_posts_from_main_query( $query );
		} );
	}

}
