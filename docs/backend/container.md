### === Container / Core.php ===

This plugin uses a container based framework.  In particular we use the Pimple container module. See [Pimple Containers](https://pimple.symfony.com/)

Containers are used for dependency injection.  Containers allow objects to be instantiated as needed and can be used to set default behaviors and properties.

The container uses anonymous functions to instantiate on an as-needed basis.

```php
$container['post_type.feed_story'] = function() use ( $container ) {
    return new Feed_Story();
}
```

Notice we keep the namespacing pattern in our containers.

The core container is defined in Core.php and is a singleton.  Only one instance will exist so no matter where it's accessed. The data and objects being passed and generated affects only the core container.

To access the core container, call `tribe_project()->container()`

---

**Factory Method**

Many classes including all post types classes which extend the Post_Object class contain a **factory** method.  This is used to retrieve an specific instance of a class.  Typically an id is passed to retrieve it.

```php
$story = Feed_Story::factory( $post_id );
echo $story->get_source_name();
```

---

**Using & Hooking Registered Classes**

Read also: [Service Providers](service-providers.md)

Using a class that's stand alone and not abstracted such as post types we can see how the container is used for more custom functionality.

Using a stock ticker class as example, the base class Ticker is never even registered to a container. In the Stock_Ticker_Service_Provider class its namespace is used but for registering the components and utilities.

```php
class Stock_Ticker_Provider implements ServiceProviderInterface {
	private $refresh_frequency = 2; // minutes

	public function register( Container $container ) {
		$container[ 'stock_ticker.feed_url' ] = 'http://xml.corporate-ir.net/irxmlclient.asp?compID=129198&reqtype=quotes';

		$container[ 'stock_ticker.cron' ] = function( Container $container ) {
			return new Cron_Scheduler( $this->refresh_frequency );
		};

		$container[ 'stock_ticker.cacher' ] = function( Container $container ) {
			return new Stock_Feed_Cacher( $container[ 'stock_ticker.feed_url' ], $container[ 'stock_ticker.feed_parser' ] );
		};

		$container[ 'stock_ticker.feed_parser' ] = function( Container $container ) {
			return new TR_Stock_Feed_Parser();
		};

		add_action( 'load-index.php', function () use ( $container ) {
			$container[ 'stock_ticker.cron' ]->schedule_cron();
		}, 10, 0 );
		add_filter( 'cron_schedules', function ( $schedules ) use ( $container ) {
			return $container[ 'stock_ticker.cron' ]->add_interval( $schedules );
		}, 10, 1 );
		add_action( Cron_Scheduler::STOCK_TICKER_CRON, function () use ( $container ) {
			$container[ 'stock_ticker.cacher' ]->refresh_cache();
		}, 10, 0 );
	}

}
```

Here we register first some data to be used by our ticker classes:

```$container[ 'stock_ticker.feed_url' ] = '....';```

Which is then used in registering the other containers

```php
$container[ 'stock_ticker.cacher' ] = function( Container $container ) {
	return new Stock_Feed_Cacher( $container[ 'stock_ticker.feed_url' ], $container[ 'stock_ticker.feed_parser' ] );
};
```

Then after registering our containers we can set any actions or filters we need to hook into.  The container comes into play as we pass it in with **use**

```php
add_action( 'load-index.php', function () use ( $container ) {
    $container[ 'stock_ticker.cron' ]->schedule_cron();
}, 10, 0 );
```

Using this method we can be sure that our singleton Container object is being used when the hook fires during the WordPress page loading process.

If the hook never fires (e.g., it's specific to admin, or certain pages, or cron jobs), then the object will never be instantiated.