<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Tribe\Libs\Container\Abstract_Subscriber;

class Post_Loop_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_filter( 'get_object_terms', function ( $terms, $object_ids, $taxonomies ) {
			return $this->container->get( Category::class )->get( $terms, $object_ids, $taxonomies );
		}, 10, 3 );
	}

}
