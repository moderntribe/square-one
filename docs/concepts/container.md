# Container / Core.php

This plugin uses a container based framework. In particular we use the [PHP-DI](http://php-di.org/)
dependency injection container that implements the [PSR-11 container interface](https://www.php-fig.org/psr/psr-11/).

Containers are used for dependency injection.  Containers allow objects to be instantiated as
needed and can be used to set default behaviors and properties.

The container uses [definition files](http://php-di.org/doc/definition.html),
[autowiring](http://php-di.org/doc/autowiring.html), and callback functions to instantiate
on an as-needed basis.

The core container is created in `Core.php` and is a singleton.  Only one instance will exist,
no matter where it's accessed. The data and objects being passed and generated affect only the
core container.

To access the core container, call `tribe_project()->container()`

---

## Factory Method

Many classes—including all post types classes (which extend `Post_Object`) and taxonomy classes
(which extend `Term_Object` )—contain a static `factory()` method.  This is used to retrieve
an specific instance of a class.  Typically an id is passed to retrieve it.

```php
$story = Feed_Story::factory( $post_id );
echo $story->get_source_name();
```

---

## Configuring the Container

For information about hooking into WordPress using the container, see: [Subscribers](subscribers.md)

PHP-DI is an autowiring container. It will use reflection to analyze typehints in requested classes
to automatically resolve dependencies, in many cases with zero configuration required. Let's take a
look at how we would define a service.

```php
namespace Tribe\Project\Activity_Analysis;

use Tribe\Libs\Queues\Contracts\Queue;

/**
 * A class responsible for tracking user activity so we can process
 * it asynchronously for analytics purposes
 */
class Activity_Listener {
    private Queue $queue;

	public function __construct( Queue $queue ) {
        $this->queue = $queue;
	}

    /**
     * @action save_post
     */
    public function post_was_saved( ... $args ) {
        $this->queue->dispatch( Analyze_Activity::class, [ 'args' => $args ] );
    }
}
```

We don't need to do anything for the DI container to know how to instantiate this class. The
`$queue` argument to the contructor has the `Queue` typehint. So the DI container will look
for the class registered for the `Queue` interface, which it finds already done in the
`Queues_Definier` class from `tribe-libs`.


Some objects do require some manual configuration, though. Some common cases where this might occur:

1. An argument lacks type hinting
2. A type hint is for a primitive value (e.g., string, array)
3. A type hint is for an abstract class or an interface
4. We want to specify a particular object or a particular subclass to satisfy the type hint

Let's look at a case where we do need to configure some of the args.

```php
namespace Tribe\Project\Activity_Analysis;

use Tribe\Libs\Queues\Contracts\Queue;

/**
 * A class responsible for tracking user activity so we can process
 * it asynchronously for analytics purposes
 */
class Activity_Listener {
    private Queue $queue;
    private int   $log_level;

	public function __construct( Queue $queue, int $log_level = 1 ) {
        $this->queue     = $queue;
        $this->log_level = $log_level;
	}

    /**
     * @action save_post
     */
    public function post_was_saved( ... $args ) {
        if ( $this->log_level >= 2 ) {
            $this->queue->dispatch( Analyze_Activity::class, [ 'args' => $args ] );
        }
    }
}
```

Since the `$log_level` argument is a primitive value rather than a class, the DI container cannot
automatically resolve its value. Instead, we need a `Definer` to configure this class.

```php
namespace Tribe\Project\Activity_Analysis;

use DI;
use Tribe\Libs\Container\Definer_Interface;

class Activity_Analysis_Definer implements Definer_Interface {
    public function define(): array {
        return [
            Activity_Listener::class => DI\autowire()
                ->constructorParameter( 'log_level', 2 ),
        ];
    }
}
```

Notice how we only need to define the parameters that cannot be automatically inferred. An alternative
way to define the class would be to bypass the autowiring and pass all of the constructor args:

```php
namespace Tribe\Project\Activity_Analysis;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Libs\Queues\Contracts\Queue;

class Activity_Analysis_Definer implements Definer_Interface {
    public function define(): array {
        return [
            Activity_Listener::class => DI\create()
                ->constructor( DI\get( Queue::class ), 2 ),
        ];
    }
}
```

Notice how we still use the DI container to get the object instances we need for the arguments.

## PhpStorm Code Navigation

PhpStorm natively detects what types of objects we retrieve at each
of the keys on the container when we pass it a class name. When calling
`$container->get()` with any other identifier, the type cannot be automatically
determined. Provide a hint with a preceeding docblock, where appropriate.

```php
/** @var Something $something */
$something = $container->get( 'my.string' );
```
