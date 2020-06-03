# Object Cache

## Overview

Docker comes with a memcache instance by default, which you can use to enable Object Caching on your site. This allows you
to see real-world examples of the `Cache` library in-action, and can help identify whether your caching strategies are effective.

## Enabling Object Cache

The file `wp-content/object-cached.php` defines a WP_Object_Cache class with sensible defaults
for working with the Object Cache within SquareOne. The presence of this class automatically
enables the WordPress object cache. 

To use a different host than the default, set the constants or environment variables `MEMCACHED_HOST` and
`MEMCACHED_PORT`.

From here you should be all set! You can now actively use the `\Tribe\Libs\Cache\Cache` class to store data to the Object Cache.

## Using the Cache

And instance of the `Cache` object should be injected into classes that require it (to make testing easier).

```php
class Example_Class {
  private $cache;
  public function __construct( \Tribe\Libs\Cache\Cache $cache ) {
    $this->cache = $cache;
  }
}
```

The `Cache` object provides a wrapper around WordPress's object caching to support more complex cache
keys and the invalidation of entire cache groups.

* `$cache->set( $key, $value, $group, $expiration )` - sets a value in the cache
* `$cache->get( $key, $value )` - retrieves the value
* `$cache->delete( $key, $group )` - deletes the value

The `$key` argument may be any serializable value, including arrays and objects.

* `$cache->flush_group( $group )` - expires all of the cache keys in the group

Group invalidation works by maintaining a generation key for the group in the cache. When the group
is flushed, the generation key will change, ensuring that stale values are never retrieved.

If you are using a caching backend that does not automatically drop old/expired references, be aware
that this strategy could result in a full cache.

## Global Functions

Three global functions are supplied for accessing the cache. Dependency injection is preferred, but these
may be used in cases where we do not have control over the constructor or method call for the client code.

* `tribe_cache_set()`
* `tribe_cache_get()`
* `tribe_cache_delete()`

These are simple wrappers around the aforementioned methods from the `Cache` class.

## Cache Invalidation

Caches should sometimes be invalidated when events occur or data changes. The class
`\Tribe\Project\Cache\Listener` is responsible for flushing keys or groups in the cache
when those events occur.

`\Tribe\Project\Cache\Cache_Subscriber::listen()` should be updated with additional actions
to call the `Listener` at the appropriate time.
