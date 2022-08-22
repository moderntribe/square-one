<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Tribe\Libs\Container\Abstract_Subscriber;

class Post_Loop_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_filter( 'get_terms', function ( $terms, $taxonomies, $args ) {
			return $this->container->get( Term_Manager::class )->get_terms( $terms, $args );
		}, 10, 3 );
	}

}
