#  === Service Providers ===

Service providers are central to the container environment used in this framework.  You'll notice in the core/src/Core.php file it is strictly used for registering service providers to the container.

The service providers handle registering components and any listeners (actions/filters) and other needed bindings.

Every custom post type, meta group, taxonomy and other components created within the core plugin will be registered inside a service provider class.

### Organization

Beyond the bootstrapping functionality of Core and the Service Providers, they don't do much but proper organization is vital to the namespace and container setup.

There are only two directories within the core/src/Service_Providers directory:

* Post_Types
* Taxonomies
 
All other service providers have their file within the Service_Providers directory.

Each post type and taxonomy have an individual service provider file whereas post meta has a single Post_Meta_Service_Provider file for handling all post meta classes.

Post types and taxonomies have service provider classes that are children of Post_Type_Service_Provider and Taxonomy_Service_Provider.

### Container

When registering a service provider stick with class names for example:

```$container['post_type.sample_post_type']```

Or if their is a utility class alongside your custom post type and in the same name space you would use

```$contianer['post_type.sample_post_type.sample_component']```

It's important to keep proper namespacing.

### Notes

The **P2P_Provider** class handles the classes that work with the **Posts 2 Posts** plugin. 

Learn more about Post 2 Post here and usage in this project here: [Posts 2 Posts](p2p.md)

## Action/Filter Hooking Pattern

Square One has adopted a lazy-loading patter for Actions and Filters. This pattern works hand-in-hand with our Service Provider pattern in order to allow all the classes in the Container to only load when they are actually requested instead of every time `plugins_loaded` is fired.

Consider the following situation illustrating the older pattern:

```
// Within the Service Provider
$container['foobar_class'] = function( $container ) {
   return new Foober_Class();
}


$container[ 'service_loader' ]->enqueue( 'foobar_class', 'hook' );


// Within Foobar_Class
public function hook() {
   add_action( 'save_post', [ $this, 'do_save_post_action' ], 20, 1 );
}

public function do_save_post_action( $post_id ) {
   // do stuff
}
```

This pattern, while effective, has a couple major drawbacks. First, since we're enqueuing the Hook method within our Service Container, and that fires every time `plugins_loaded` is called, our Foobar_Class is instantiated. This causes bootstrapping WordPress to be a lot more memory-and-performance intensive. At that point, whether you ever need to use Foobar_Class or not on a particular load, it's going to get initialized.

Secondly, this pattern causes the Class definitions and the Hooking to exist in two different locations, which can make discovery difficult. While IDEs and code hinting can make this less of an issue, having both definitions and Hooks within the same Service Provider makes things much easier to follow for anyone coming to the codebase for the first time. 

By contrast, consider the updated pattern:

```
// Service Provider
$container['foobar_class'] = function( $container ) {
   return new Foober_Class();
}

add_action( 'save_post', function( $post_id ) use ( $container ) {
   $container['foobar_class']->do_save_post_action( $post_id );
}, 20, 1 );

// In Foobar_Class
public function do_save_post_action( $post_id ) {
   // do stuff
}
```

By adding the action directly in the Service Provider, Foober_Class is only instantiated whenever it is needed (in this case, when `save_post` is called). So for any load in which `save_post` is *not* called, Foobar_Class does not get initiated, saving the extra time and memory that it would normally consume.

Additionally, now the Class Definition and the Hooking all exist in the same location, making it very simple to see which classes exist in the Container, and the contexts in which they will be fired from an Action or Filter.

By moving to this pattern, we've seen substantial performance gains on systems of every size.

### More Examples

#### Actions

*Adding a custom admin menu screen*
```
// Service Provider
$container['sync.registry'] = function ( $container ) {
	return new Sync_Registry( $container['plugin_file'] );
};

add_action( 'admin_menu', function () use ( $container ) {
	$container['sync.registry']->add_sync_screen();
} );

// Sync_Registry
public function add_sync_screen() {
	add_management_page( self::TITLE, self::TITLE, 'sync_registry', self::SLUG, [ $this, 'render_sync_screen' ] );
}

```

*Registering REST API Routes*

```
// Service Provider
$container['routes.post'] = function ( $container ) {
	return new Route_Post();
};

add_action( 'rest_api_init', function() use ( $container ) {
	$container['routes.post']->register_routes();
} );

// Route_Post
public function register_routes() {
	register_rest_route( self::ROUTE_NAMESPACE, '/' . self::BASE, [
		[
			'methods'  => \WP_REST_Server::CREATABLE,
			'callback' => [ $this, 'create_item' ],
			'args'     => $this->get_post_args(),
		],
	] );
}
```

#### Filters

When adding a filter that has a return, it's important to remember to *return* the result within the closure. 

*Registering a custom Twig Handler*

```
// Service Provider
$container[ 'twig' ] = function ( Container $container ) {
	$twig = new \Twig_Environment( $container[ 'twig.loader' ], $container[ 'twig.options' ] );
	$twig->addExtension( $container[ 'twig.extension' ] );
	$twig->addExtension( new \Twig_Extension_Debug() );
	return $twig;
};

// Note that we return within the closure - otherwise the add_filter call will return null, thus breaking the call chain.
add_filter( 'tribe/project/twig', function ( $twig ) use ( $container ) {
	return $container[ 'twig' ];
}, 0, 1 );
```

*Adding in custom column names in an Edit Post table*

```
// Service Provider
$container['posts.fork'] = function ( Container $container ) {
	return new Fork();
};

// Note that the argument for the closure is whatever the particular filter passes for arguments
add_filter( 'manage_posts_columns', function ( $columns ) use ( $container ) {
	return $container['posts.fork']->replace_title_column( $columns );
}, 10, 1 );

// Fork
public function replace_title_column( $columns ) {
	$start = array_slice( $columns, 0, 1, true );
	$end   = array_slice( $columns, 2, null, true );

	return $start + [ self::TITLE_COLUMN => 'Title' ] + $end;
}
```

*Adding Quick Edit Links*

```
// Service Provider
$container['posts.fork'] = function ( Container $container ) {
	return new Fork();
};

// Note that we still need to define the action priority (99) and the # of arguments passed (2); otherwise the defaults will be used
add_filter( 'post_row_actions', function ( $actions, $post ) use ( $container ) {
	return $container['posts.fork']->add_quick_edit_links( $actions, $post );
}, 99, 2 );

// Fork
public function add_quick_edit_links( $actions, \WP_Post $post ) {
	// do stuff
}
```

*Passing an indeterminate number of arguments*

There are times when you may wish to pass an indeterminate number of arguments to your action or filter. This can be accomplished
using the `...$args` parameter pattern. Keep in mind that it's important to set the final argument of `add__filter()` to 
something large (here we use 99) to ensure WordPress passes all of your arguments.

```
// Service Provider
$container['posts.fork'] = function ( Container $container ) {
	return new Fork();
};

// Note the use of ...$args here. This will pass any # of arguments through the closure to the call on the container's class.
add_filter( 'post_row_actions', function ( ...$args ) use ( $container ) {
	return $container['posts.fork']->add_quick_edit_links( ...$args );
}, 10, 99 );

// Fork
public function add_quick_edit_links( $actions, \WP_Post $post ) {
	// do stuff
}
```
