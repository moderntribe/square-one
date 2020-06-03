# Container Subscribers

While all of the classes for the DI container are either [autowired or configured
in defintion files](container.md), we still need to hook those classes into
WordPress to make them do something. The responsibility of registering handlers
for actions and filters belongs in subscriber classes.

A subscriber will implement `\Tribe\Libs\Container\Subscriber_Interface`, implementing
a `register()` method that serves as the entrypoint for configuring hook callbacks.

## Action/Filter Hooking Pattern

SquareOne has adopted a lazy-loading patter for Actions and Filters. This pattern
works hand-in-hand with our container pattern in order to allow all the classes in
the container to only load when they are actually requested instead of every time
`plugins_loaded` is fired.

Consider the following situation illustrating an anti-pattern:

```php
// Within the subscriber
$container->get( Foo::class )->hook();
```

```php
// Within Foobar_Class
public function hook() {
   add_action( 'save_post', [ $this, 'do_save_post_action' ], 20, 1 );
}
```

This pattern, while effective, has a couple major drawbacks. First, since we're calling the
`hook()` method within our subscriber, and that fires every time `plugins_loaded` is called,
our `Foo` class is instantiated even when a post is not saved. This causes bootstrapping WordPress
to be a lot more memory-and-performance intensive. At that point, whether you ever need to use
a `Foo` instance or not on a particular load, it's going to get initialized.

By contrast, consider the preferred pattern:

```php
// Within the subscriber
add_action( 'save_post', function( $post_id ) use ( $container ) {
   $container->get( Foo::class )->do_save_post_action( $post_id );
}, 20, 1 );

// In Foobar_Class
/**
 * @param int $post_id
 * @return void
 * @action save_post 20
 */
public function do_save_post_action( $post_id ): void {
   // do stuff
}
```

By adding the action directly in the subscriber, `Foo` is only instantiated when it is needed
(in this case, when `save_post` is called). So for any load in which `save_post` is *not* called,
`Foo` does not get instantiated, saving the extra time and memory that it would normally consume.

By moving to this pattern, we've seen substantial performance gains on systems of every size.


### Filters

When adding a filter, it is important to remember to *return* the result from the closure. 


Example: Adding in custom column names in an Edit Post table

```php
// In the subscriber
// Note that the argument for the closure is whatever the particular filter passes for arguments
add_filter( 'manage_posts_columns', function ( $columns ) use ( $container ) {
	return $container->get( My_Class::class )->replace_title_column( $columns );
}, 10, 1 );

// In My_Class
/**
 * @param array $columns
 * @return array
 * @filter manage_posts_columns
 */
public function replace_title_column( array $columns ): array {
	$start = array_slice( $columns, 0, 1, true );
	$end   = array_slice( $columns, 2, null, true );

	return $start + [ self::TITLE_COLUMN => 'Title' ] + $end;
}
```

### Notes

The **P2P_Subscriber** class handles the classes that work with the **Posts 2 Posts** plugin. 

Learn more about Post 2 Post here and usage in this project here: [Posts 2 Posts](p2p.md)

## Why Class LazyLoading?

Previously we covered the pattern of registering classes within the definter, and registering
hooks/actions/filters within the subscriber (vs. registering them, say, in a `hooks()` method
on the class itself).

There has been a bit of debate and confusion about why exactly we landed on this pattern. As such,
here are but a few reasons why we believe it is the best approach:

* Separating the hooks from the class encourages separation of responsibilities. A class
  should be responsible for doing a thing. “Filtering a query” and “Hooking that filter into WordPress”
  are two different responsibilities. It’s very easy to write a unit/integration test for “Filtering
  a query”. Harder to write a good test for “hooked into wordpress such that when a certain event
  occurs this thing runs and gives a different result”. The latter falls into the realm of acceptance tests.
* Separating the hooks from the class encourages us to think about our classes in isolation from WordPress.
  Sure, we’re operating 99% of the time in the context of WordPress. But that doesn’t mean that every
  single one of our classes should be tightly coupled to WP core.
* Lazy instantiation of our classes is _sometimes_ a performance gain. Yes, a simple constructor
  with no args is quite cheap to instantiate; approximately the same cost as instantiating a closure.
  A lot of our constructors do not meet that definition, though. Some classes instantiate dozens
  of other objects. Some classes run queries. Some classes have a lot of dependencies to inject
  that may be expensive to instantiate. Could these classes be better designed? Yes, probably.
  But lazy instantiation is a quick and easy method to get significant performance gains. (For
  example, Steelcase saw something in the neighborhood of 20% performance increases when we switched
  it over to this pattern, resulting in ~500ms off every uncached response).
* Lazy instantiation allows us to define a class and all of its dependencies at a point in the page
  load that some data may not be available. E.g., if we need to inject a dependency into a class
  that depends on the value of `get_queried_object_id()`, we can’t do that until after the `wp` hook.
  But we’re defining all of our instances on `plugins_loaded`, so the data is not yet available.
  The alternatives are to have multiple hooks on which we instantiate our classes, OR remove the
  dependency injection that makes our code more testable.
* In light of the above points, _some_ classes could be instantiated cheaply, while others are
  more expensive. Is it worth the cognitive overhead of deciding on a case by case basis which
  category a given class falls into? No. Build habits around a reasonable and performant default.
  Only deviate from it if you have a strongly compelling reason.
* Lazy instantiation gives us the opportunity to override the definitions of our objects. A
  common example is a WP multisite instance (we do a lot of those) where most sites will have
  one default behavior, but a small collection of sites will need to change that behavior in some
  way (I can give you lots of examples if you need them). Our default definers will define
  all the default objects that we assign to the container, but then we can load overrides based
  on arbitrary criteria (blog ID, active plugins, theme, etc.) that re-define the container’s keys
  before they are instantiated. The closure is still hooked into WP, but the class instances that
  handle the callback are swapped out because of the overrides.
* By moving all of the hooks to the subscriber, we do separate them from the code that will be
  handling the hooks. But we also colocate them with other hooks handled by other classes that may
  be related. This makes it easier to orchestrate multiple classes that work together to accomplish
  an end result, while keeping those classes separate for easier testing and more clear separation
  of responsibilities.

While the move to Class LazyLoading hasn't been without it's drawbacks, we believe the tradeoff
in performance, testability, separation of concerns, and extensibility is well worth it.
