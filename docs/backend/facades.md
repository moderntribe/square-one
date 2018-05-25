# Facades

Facades allow classes to be accessed statically within classes which may not be candidates for Dependency Injection. This
allows your code to call instances of these classes without having to instantiate a new instance every time you need it.

By default, Facades simply return the class registered to the Container within a Service Provider. This allows a single instance
to be re-used throughout the codebase. 

## Creating a Facade

To create a Facade, simply create a class which extends `Tribe\Project\Facade\Facade`. 

A Facade need only have a single method: `get_container_key_accessor()`. This method should return a string matching the 
key with which the given Class was registered in the Container. For example, if we registered a class `Foobar` in the container
as so:

```php
$container['foobar'] = function() use ( Container $container ) {
    return new Foobar();
}
```

Then our Foobar Facade would look like so:

```php

class Foobar extends Facade {
    
    public function get_container_key_accessor() {
        return 'foobar';
    }
}
```

When the `Foobar` Facade is then called statically, it will return the `tribe_project()->container()['foobar']` instance 
already registered and pass the method on from there. 

## Example

```php

class Foobar {
    
    public function add( $first, $second ) {
        return $first + $second;
    }
}

class Foobar extends Facade {
    
    public function get_container_key_accessor() {
        return 'foobar';
    }
}

// in Service Provider

$container['foobar'] = function() use ( Container $container ) {
    return new Foobar();
}
```

We could then access Foobar statically as such:

```php
use Tribe\Project\Facade\Items\Foobar;

$sum = Foobar::add( 2, 3 );

echo $sum;

```

Which would echo:

```php
5
```

## Testing with Facades

In order to provide the most-testable situation possible, Facades can also be mocked using the Codeception Stub mocking methods.
When a Facade is mocked, any call to that Facade will return that mock instead of the real instance. This allows you to mock 
your Facade classes without having to pass that mock to the class being tested. For instance:

```php

class Tester {
    
    public function get_content_type() {
        return Request::header('Content-Type');
    }

}

// in your test

$tester = new Tester();

$tester->get_content_type();

// probably null since a request doesn't actually exist.

Request::make( [ 'header' => function(){ return 'test_value'; } ] );

$tester->get_content_type();

// returns "test_value" since Request now uses the mock.

Request::destroy_mock();

$tester->get_content_type();

// returns null again since we destroyed the mock.

```

## When Should I Use a Facade?

Almost never! Facades should really only be used when you're dealing with a class in which you do _not_ control the Constructor. 
If you _do_ control the Constructor, use Dependency Injection! It's easier to test and avoids global scope.

However, there are times when you don't (at least in a direct fashion) have control over the Constructor - for instance in a 
Controller. In these instances, using a Facade makes sense as Controllers aren't generally tested and injecting the class 
may be convoluted. 

Here's a handy guide to using Facades:

- Do you control the Constructor? _Do not_ use Facades. Use Dependency Injection! Dependency Injection is wonderful.
Dependency Injection is your friend. 9/10 times, DI is your answer.

- Do you _really_ not control the Constructor and don't need to test your class? Go ahead and use a Facade! It'll still use
the Container instance and make your life easier.

- Do you not control the Constructor but still need to test your class and mock your Facade? Use the Facade, and in your tests
use `Your_Facade::make()` or any of the other Codeception Stub methods to create a mock. Your class will then use the mock version
of the Facade instead of the real class instance.