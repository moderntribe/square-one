# Object Cache

## Overview

Docker comes with a memcache instance by default, which you can use to enable Object Caching on your site. This allows you
to see real-world examples of the `Cache` library in-action, and can help identify whether your caching strategies are effective.

## Enabling Object Cache

The first step to enable Object Cache is to copy `object-cache-sample.php` to `object-cache.php`. This file defines a WP_Object_Cache 
class with sensible defaults for working with the Object Cache within SquareOne. 

The final step is to ensure that your `local-config.php` file is properly defining the `memcached_server` global. The
`local-config-sample.php` file has the correct line already, so if you copied your `local-config` file from that, you should be all set! 
If not, make sure you have a line reading:

```
$GLOBALS[ 'memcached_servers' ] = [ 'memcached:11211' ];
```

This lets `object-cache.php` know that you want to use the `memcached` instance in Docker with the port of `11211`.

From here you should be all set! You can now actively use the `Tribe\Libs\Cache` class to store data to the Object Cache.
