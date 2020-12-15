# Posts to Posts (P2P)

## Overview

P2P is a plugin that has been included in the Tribe library for SquareOne.  
It is a stand alone plugin available in the WordPress repo and developer 
documentation can be found here: [Posts 2 Post](https://github.com/scribu/wp-posts-to-posts/wiki).

P2P is used to store a connection between two posts of any post type and/or users.  The connection
is represented by a relationship type.  We use a subclass of the Tribe library relationship to define
what connection types we want in our project.  For instance, post-to-event or page-to-post.

## Defining a Relationship

Defining a relationship is done in two places.  The first is creating your `Relationship` subclass like
this example:

```php
<?php

namespace Tribe\Project\P2P\Relationships;

use Tribe\Libs\P2P\Relationship;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Sample\Sample;

class Sample_To_Page extends Relationship {

	public const NAME = 'Sample_To_Page';

	protected $from = [
		Sample::NAME,
	];

	protected $to = [
		Page::NAME,
	];

	public function get_args() {
		return [
			'reciprocal'      => true,
			'cardinality'     => 'many-to-many',
			'admin_box'       => [
				'show'    => 'any',
				'context' => 'side',
			],
			'title'           => [
				'from' => __( 'Related Pages', 'tribe' ),
				'to'   => __( 'Related Samples', 'tribe' ),
			],
			'from_labels'     => [
				'singular_name' => __( 'Sample', 'tribe' ),
				'search_items'  => __( 'Search', 'tribe' ),
				'not_found'     => __( 'Nothing found.', 'tribe' ),
				'create'        => __( 'Relate Sample', 'tribe' ),
			],
			'to_labels'       => [
				'singular_name' => __( 'Page', 'tribe' ),
				'search_items'  => __( 'Search', 'tribe' ),
				'not_found'     => __( 'Nothing found.', 'tribe' ),
				'create'        => __( 'Relate Page', 'tribe' ),
			],
			'can_create_post' => false,
		];
	}
}
```

This class is used to relate the Sample post type to the Page post type.  The get_args() method
is required of the Relationship subclass.

In order to see the relationship meta boxes in the post types you need to register this in the
P2P definer.

```php
\Tribe\Libs\P2P\P2P_Definer::RELATIONSHIPS  => DI\add( [
    DI\get( General_Relationship::class ),
    DI\get( Sample_To_Page::class ),
])
```

## Connections Helper Class

The `Connections` class helps avoid convoluted 
methodology for simple needs in the core posts-to-posts plugin.

To use the connections class, get an instance (or use the DI container to inject
it into your class) and use the helper methods as needed.

```php
$p2p = new \Tribe\Libs\P2P\Connections();
$connected_ids = $p2p->get_from( $post_id );

foreach( $connected_ids as $post_id ) {
  // do things
}
```

The above will get any and all post ids connected to our $post_id no matter what the post type.
It will only get the posts that are in the p2p_to column though as we are getting connections FROM 
our $post_id

```php
$page_ids = $p2p->get_from( $post_id, [ 'type' => Sample_To_Page::NAME ] );

foreach( $page_ids as $page_id ) {
  // do things
}
```

The above example will do the same as the previous except restrict results to only those belonging to
the specified connection type.

In both the get_from() and get_to methods the 2nd $args parameter has some useful options.

- **type**: This can be a string of a single connection type or an array of multiple connection types
- **order**: This can be set to 'ASC' or 'DESC' and will order by the p2p_id field unless specified in orderby
- **orderby**: This can be set to 'ids' to sort by the resulting ids from the query
- **meta**: This is an array that can return only results that have a specific meta key and optionally a value for that key

Here's an example using the meta arguments

```php
$args = [
    'type' => Sample_To_Page::NAME,
    'meta' => [
        'key' => 'some_meta_key',
        'value' => 'optional meta value',
    ],
];
$page_ids = $p2p->get_from( $post_id, $args );

foreach( $page_ids as $page_id ) {
  // do things
}
```

Another method available in the `Connections` class is the `get_shared_connections()` method.
Simply sending an id to this method will return all connections it has of any type.

The type parameter accepts an array of types to filter results.  The direction parameter accepts
"to" or "from" and will align all results where the passed id is in the defined direction field.

