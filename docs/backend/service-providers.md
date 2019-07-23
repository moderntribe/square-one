# Service Providers

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

SquareOne has adopted a lazy-loading patter for Actions and Filters. This pattern works hand-in-hand with our Service Provider pattern in order to allow all the classes in the Container to only load when they are actually requested instead of every time `plugins_loaded` is fired.

Consider the following situation illustrating the older pattern:

```php
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

```php
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

```php
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

```php
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

```php
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

```php
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

```php
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

```php
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

## Why Class LazyLoading?

Previously we covered the pattern of registering classes within the Service Provider, and _also_ registering hooks/actions/filters 
within that same Provider (vs. registering them, say, in a `hooks()` method on the class itself).

There has been a bit of debate and confusion about why exactly we landed on this pattern. As such, here are but a few reasons why 
we feel it's the best approach:

* Separating the hooks from the class encourages separation of responsibilities. A class should be responsible for doing a thing. “Filtering a query” and “Hooking that filter into WordPress” are two different responsibilities. It’s very easy to write a unit/integration test for “Filtering a query”. Harder to write a good test for “hooked into wordpress such that when a certain event occurs this thing runs and gives a different result”. The latter falls into the realm of acceptance tests.
* Separating the hooks from the class encourages us to think about our classes in isolation from WordPress. Sure, we’re operating 99% of the time in the context of WordPress. But that doesn’t mean that every single one of our classes should be tightly coupled to WP core.
* Lazy instantiation of our classes is _sometimes_ a performance gain. Yes, a simple constructor with no args is quite cheap to instantiate; approximately the same cost as instantiating a closure. A lot of our constructors do not meet that definition, though. Some classes instantiate dozens of other objects. Some classes run queries. Some classes have a lot of dependencies to inject that may be expensive to instantiate. Could these classes be better designed? Yes, probably. But lazy instantiation is a quick and easy method to get significant performance gains. (For example, Steelcase saw something in the neighborhood of 20% performance increases when we switched it over to this pattern, resulting in ~500ms off every uncached response).
* Lazy instantiation allows us to define a class and all of its dependencies at a point in the page load that some data may not be available. E.g., if we need to inject a dependency into a class that depends on the value of `get_queried_object_id()`, we can’t do that until after the `wp` hook. But we’re defining all of our instances on `plugins_loaded`, so the data is not yet available. The alternatives are to have multiple hooks on which we instantiate our classes, OR remove the dependency injection that makes our code more testable.
* In light of the above points, _some_ classes could be instantiated cheaply, while others are more expensive. Is it worth the cognitive overhead of deciding on a case by case basis which category a given class falls into? No. Build habits around a reasonable and performant default. Only deviate from it if you have a strongly compelling reason.
* Lazy instantiation gives us the opportunity to override the definitions of our objects. A common example is a WP multisite instance (we do a lot of those) where most sites will have one default behavior, but a small collection of sites will need to change that behavior in some way (I can give you lots of examples if you need them). Our default service providers will define all the default objects that we assign to the container, but then we can load overrides based on arbitrary criteria (blog ID, active plugins, theme, etc.) that re-assign the container’s keys before they are instantiated. The closure is still hooked into WP, but the class instances that handle the callback are swapped out because of the overrides.
* By moving all of the hooks to the service provider, we do separate them from the code that will be handling the hooks. But we also colocate them other hooks handled by other classes that may be related. This makes it easier to orchestrate multiple classes that work together to accomplish an end result, while keeping those classes separate for easier testing and more clear separation of responsibilities.

While the move to Class LazyLoading hasn't been without it's drawbacks (chief amongst them being having more concise classes with self-contained calls to actions and filters), we feel the tradeoff in performance, testability,
separation of concerns, and extensibility is well worth it.
