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
to automatically resolve dependencies, in many cases with zero configuration required. Let's take one
of our template controllers as an example.

```php

class Page extends Abstract_Template {
	// property declarations at the top

	public function __construct(
		Environment $twig,
		Component_Factory $factory,
		Header $header,
		Subheader $subheader,
		Main_Sidebar $sidebar,
		Footer $footer
	) {
		parent::__construct( $twig, $factory );
		$this->header    = $header;
		$this->subheader = $subheader;
		$this->sidebar   = $sidebar;
		$this->footer    = $footer;
	}

	// and more functions below
}
```

To instantiate a `Page` template, we call `tribe_project()->container()->get( Page::class )`. This
returns an instance with all of the constructor arguments resolved by the autowiring. Nowhere do
we have to tell the container which `Header` or which `Footer` to pass into the `Page`. The type
hinting is sufficient to communicate that to the container. It's as if we had called
`tribe_project()->container()->get( Header::class )` and `tribe_project()->container()->get( Footer::class )`
to get the arguments to pass.

Some objects do require some manual configuration, though. Some common cases where this might occur:

1. An argument lacks type hinting
2. A type hint is for a primitive value (e.g., string, array)
3. A type hint is for an abstract class or an interface
4. We want to specify a particular object or a particular subclass to satisfy the type hint

When we need to configure the container, we do that by passing a definition file to the 
`ContainerBuilder` from the `Core` class. We will do this in SquareOne using classing that
implement `\Tribe\Libs\Container\Definer_Interface`. Each definer with implement
the `define()` method, returning an array of definitions for the container.

Continuing our example above, the `Page` class requires a `Twig\Environment`, which will require
some configuration when we first instantiate it. This definition comes from the `Twig_Definer` class
in the core plugin. In that definer, we'll find:

```php
Environment::class => DI\autowire()
	->constructorParameter( 'options', DI\get( self::OPTIONS ) )
	->method( 'addExtension', DI\get( Extension::class ) )
```

With this definition, we tell the container that the `Environment` should be instantiated
with a particular value for the `$options` parameter in the constructor (defined elsewhere
in the file, at `Twig_Definer::OPTIONS`), and that the `addExtension()` method should be
called immediately on instantiation so that we can add our `Extension` from the core plugin.

In `Core`, we add `Twig_Definer::class` to the array in the `$definers` property so that the
definitions from the file will be added to the container.

## PhpStorm Code Navigation

PhpStorm natively detects what types of objects we retrieve at each
of the keys on the container when we pass it a class name. When calling
`$container->get()` with any other identifier, the type cannot be automatically
determined. Provide a hint with a preceeding docblock, where appropriate.

```php
/** @var Something $something */
$something = $container->get( 'my.string' );
```
