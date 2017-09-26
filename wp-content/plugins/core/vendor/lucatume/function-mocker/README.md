# Function Mocker

*A [Patchwork](http://antecedent.github.io/patchwork/) powered function mocker.*

[![Build Status](https://travis-ci.org/lucatume/function-mocker.svg?branch=master)](https://travis-ci.org/lucatume/function-mocker)

## Show me the code
This can be written in a [PHPUnit](http://phpunit.de/) test suite
    
```php
<?php

// FunctionMocker needs the functions to be defined to replace them
function get_option($option)
{
    // no-op
}

function update_option($option, $value)
{
    // no-op
}

// The class under test
class Logger
{
    public function log($type, $message)
    {
        $option = get_option('log');
        $option[] = sprintf('[%s] %s - %s', date(DATE_ATOM, time()), $type, $message);
        update_option('log', sprintf('[%s] %s - %s', date(DATE_ATOM, time()), $type, $message));
    }
}

class InternaFunctionReplacementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * It should log the correct message
     * @test
     */
    public function log_the_correct_message()
    {
        $mockTime = time();
        \tad\FunctionMocker\FunctionMocker::replace('time', $mockTime);
        \tad\FunctionMocker\FunctionMocker::replace('get_option', []);
        $update_option = \tad\FunctionMocker\FunctionMocker::replace('update_option');

        $logger = new Logger();

        $logger->log('error', 'There was an error');

        $expected = sprintf('[%s] error - There was an error', date(DATE_ATOM, $mockTime));
        $update_option->wasCalledWithOnce(['log', $expected]);
    }
}
```

## Installation
Either zip and move it to the appropriate folder or use [Composer](https://getcomposer.org/)

    composer require lucatume/function-mocker:~1.0

## Usage
In a perfect world you should never need to mock static methods and functions, should use [TDD](http://en.wikipedia.org/wiki/Test-driven_development) to write better object-oriented code and use it as a design tool.  
But sometimes a grim and sad need to mock those functions and static methods might arise and this library is here to help.

### Bootstrapping
To make Fucntion Mocker behave in its wrapping power (a power granted by [patchwork](https://github.com/antecedent/patchwork)) the `FunctionMocker::init` method needs to be called in the proper bootstrap file of [Codeception](http://codeception.com/) or [PHPUnit](http://phpunit.de/) like this

```php
<?php
// This is global bootstrap for autoloading
use tad\FunctionMocker\FunctionMocker;

require_once dirname( __FILE__ ) . '/../vendor/autoload.php';

FunctionMocker::init(['blacklist' => dirname(__DIR__)]);
```

The `init` method will accept a configuration array supporting the following arguments:

* `include` or `whitelist` - array|string; a list of **absolute** paths that should be included in the patching.
* `exclude` or `blacklist` - array|string; a list of **absolute** paths that should be excluded in the patching.
* `cache-path` - string; the **absolute** path to the folder where Pathchwork should cache the wrapped files.
* `redefinable-internals` - array; a list of internal PHP functions that are available for replacement; if an internal function (a function defined by PHP) is not listed here then it will never be replaced in the tests.


```php
\tad\FunctionMocker\FunctionMocker::init([
    'whitelist' => [dirname(__DIR__) . '/src',dirname(__DIR__) . '/vendor'],
    'blacklist' => [dirname(__DIR__) . '/included', dirname(__FILE__) . 'patchwork-cache', dirname(__DIR__)],
    'cache-path' => dirname(__DIR__) . 'patchwork-cache,
    'redefinable-internals' => ['time', 'filter_var']
]);
```

Excluding the project root folder, `dirname(__DIR__)` in the examples, is usually a good idea.

**Note**: the library will ignore `patchwork.json` files even if no configuration is provided to the `FunctionMocker::init` method.

#### Initialization parameters
Function mocker will take care of initializing Patchwork with some sensible defaults but those initialization parameters can be customized:

 * `whitelist` - array or string, def. empty; a list of absolute paths that should be included in the patching.
 * `blacklist` - array or string, def. empty; a list of absolute paths that should be excluded from the patching; the Patchwork library itself and the Function Mocker library are always excluded.
 * `cache-path` - string, def. `cache` folder; the absolute path to the folder where Pathcwork should cache the wrapped files.
 * `redefinable-internals`   array, def. empty; a list of internal PHP functions that are available for replacement; any *internal* function (defined by the PHP Standard Library) that needs to be replaced in the tests should be listed here.

### setUp and tearDown methods  
The library is meant to be used in the context of a [PHPUnit](http://phpunit.de/) test case and provides two `static` methods that **must** be inserted in the test case `setUp` and `tearDown` method for the function mocker to work properly:

```php
class MyTest extends \PHPUnit_Framework_TestCase {
    public function setUp(){
        // before any other set up method
        FunctionMocker::setUp();
        ...
    }

    public function tearDown(){
        ...

        // after any other tear down method
        FunctionMocker::tearDown();
    }
}
```

### Functions

#### Replacing functions
The library will allow for replacement of **defined and undefined** functions at test run time using the `FunctionMocker::replace` method like:

```php
FunctionMocker::replace('myFunction', $returnValue);
```

and will allow setting a return value as a real value or as a function callback:

```php    
public function testReplacedFunctionReturnsValue(){
    FunctionMocker::replace('myFunction', 23);

    $this->assertEquals(23, myFunction());
}

public fuction testReplacedFunctionReturnsCallback(){
    FunctionMocker::replace('myFunction', function($arg){
            return $arg + 1;
        });

    $this->assertEquals(24, myFunction());
}
```

#### Spying on functions
If the value returned by the `FunctionMocker::replace` method is stored in a variable than checks for calls can be made on that function:

```php  
public function testReplacedFunctionReturnsValue(){
    $myFunction = FunctionMocker::replace('myFunction', 23);

    $this->assertEquals(23, myFunction());

    $myFunction->wasCalledOnce();
    $myFunction->wasCalledWithOnce(23);
}
```

The methods available for a function spy are the ones listed below in the "Methods" section.

#### Batch replacement of functions
When in need to stub out a batch of functions needed for a component to work this can be done:
    
```php
public function testBatchFunctionReplacement(){
    $functions = ['functionOne', 'functionTwo', 'functionThree', ...];

    FunctionMocker::replace($functions, function($arg){
        return $arg;
        });

    foreach ($functions as $f){
        $this->assertEquals('foo', $f('foo'));
    }
}
```

When replacing a batch of functions the return value will be an `array` of spy objects that can be referenced using the function name:

```php
public function testBatchFunctionReplacement(){
    $functions = ['functionOne', 'functionTwo', 'functionThree', ...];

    $replacedFunctions = FunctionMocker::replace($functions, function($arg){
        return $arg;
        });
    
    functionOne();

    $functionOne = $replacedFunctions['functionOne'];
    $functionOne->wasCalledOnce();

}
```

### Static methods

#### Replacing static methods
Similarly to functions the library will allow for replacement of **defined** static methods using the `FunctionMocker::replace` method

```php
public function testReplacedStaticMethodReturnsValue(){
    FunctionMocker::replace('Post::getContent', 'Lorem ipsum');

    $this->assertEquals('Lorem ipsum', Post::getContent());
}
```

again similarly to functions a callback function return value can be set:

```php
public function testReplacedStaticMethodReturnsCallback(){
    FunctionMocker::replace('Post::formatTitle', function($string){
        return "foo $string baz";
        });

    $this->assertEquals('foo lorem baz', Post::formatTitle('lorem'));
}
```

>Note that only `public static` methods can be replaced.

#### Spying of static methods
Storing the return value of the `FunctionMocker::replace` function allows spying on static methods using the methods listed in the "Methods" section below like:

```php
public function testReplacedStaticMethodReturnsValue(){
    $getContent = FunctionMocker::replace('Post::getContent', 'Lorem ipsum');

    $this->assertEquals('Lorem ipsum', Post::getContent());

    $getContent->wasCalledOnce();
    $getContent->wasNotCalledWith('some');
    ...
}
```

#### Batch replacement of static methods
Static methods too can be replaced in a batch assigning to any replaced method the same return value or callback:

```php
public function testBatchReplaceStaticMethods(){
    $methods = ['Foo::one', 'Foo::two', 'Foo::three'];

    FunctionMocker::replace($methods, 'foo');

    $this->assertEquals('foo', Foo::one());
    $this->assertEquals('foo', Foo::two());
    $this->assertEquals('foo', Foo::three());
}
```

When batch replacing static methods `FunctionMocker::replace` will return an array of spy objects indexed by the method name that can be used as any other static method spy object;

```php
public function testBatchReplaceStaticMethods(){
    $methods = ['Foo::one', 'Foo::two', 'Foo::three'];

    $replacedMethods = FunctionMocker::replace($methods, 'foo');
    
    Foo::one();

    $one = $replacedMethods['one'];
    $one->wasCalledOnce();
}
```

### Instance methods

#### Replacing instance methods
When trying to replace an instance method the `FunctionMocker::replace` method will return an extended PHPUnit mock object implementing all the [original methods](https://phpunit.de/manual/current/en/test-doubles.html) and some (see below)

```php
// file SomeClass.php

class SomeClass{

    protected $dep;

    public function __construct(Dep $dep){
        $this->dep = $dep;
    }

    public function someMethod(){
        return $this->dep->go();
    }
}

// file SomeClassTest.php   

use tad\FunctionMocker\FunctionMocker;

class SomeClassTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function it_will_call_go(){
        $dep = FunctionMocker::replace('Dep::go', 23);

        $sut = new SomeClass($dep);

        $this->assertEquals(23, $sut->someMethod());
    }
}
```

The `FunctionMocker::replace` method will set up the PHPUnit mock object using the `any` method, the call above is equivalent to

```php
$dep->expects($this->any())->method('go')->willReturn(23);
```

An alternative to this instance method replacement exists if the need arises to replace more than one instance method in a test:

```php
use tad\FunctionMocker\FunctionMocker;

class SomeClassTest extends \PHPUnit_Framework_TestCase {
    
    public function dependencyTest(){

        $func = function($one, $two){
                return $one + $two;
            };

        $mock = FunctionMocker::replace('Dependency')
            ->method('methodOne') // replace with null returning methods
            ->method('methodTwo', 23) // replace the method and return a value
            ->method('methodThree', $func)
            ->get();

        $this->assertNull($mock->methodOne());
        $this->assertEquals(23, $mock->methodTwo());
        $this->assertEquals(4, $mock->methodThree(1,3));

    }
}
```

Not specifying any method to replace will return a mock object wher just the `__construct` method has been replaced.

#### Mocking chaining methods
Since version `0.2.13` it's possible mocking instance methods meant to be chained. Given the following dependency class
    
```php
class Query {

    ...

    public funtion where($column, $condition, $constraint){
        ...

        return $this;
    }

    public function getResults(){
        return $this->results;
    }

    ...

}
```

and a possible client class
    
```php
class QueryUser{

    ...

   public function getOne($id){
    $this->query
        ->where('ID', '=', $id)
        ->where('type', '=', $this->type)
        ->getFirst();
   }

   ...

}
```

mocking the self-returning `where` method is possible in a test case using the `->` as return value
    
```php
public function test_will_call_where_with_proper_args(){
    // tell FunctionMocker to return the mock object itself when
    // the `where` method is called
    FunctionMocker::replace('Query::where', '->');
    $query = FunctionMocker::replace('Query::getFirst', $mockResult);
    $sut = new QueryUser();
    $sut->setQuery($query);
    
    // execute
    $sut->getOne(23);

    // verify
    ...
}
```

#### Mocking abstract classes, interfaces and traits
Relying on PHPUnit instance mocking engine FunctionMocker retains its ability to mock interfaces, abstract classes and traits; the syntax to do so is the same used to mock instance methods 

```php
interface SalutingInterface {
    public function sayHi();
}
```

the interface above can be replaced in a test like this

```php
public function test_say_hi(){
    $mock = FunctionMocker::replace('SalutingInterface::sayHi', 'Hello World!');
    
    // passes
    $this->assertEquals('Hello World!', $mock->sayHi());
}
```

See PHPUnit docs for a more detailed approach.

#### Spying instance methods
The object returned by the `FunctionMocker::replace` method called on an instance method will allow for the methods specified in the "Methods" section to be used to check for calls made to the replaced method:

```php
// file SomeClass.php

class SomeClass{

    public function methodOne(){
        ...
    }

    public function methodTwo(){
        ...
    }
}

// file SomeClassTest.php   

use tad\FunctionMocker\FunctionMocker;

class SomeClassTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function returns_the_same_replacement_object(){
        // replace both class instance methods to return 23
        $replacement = FunctionMocker::replace('SomeClass::methodOne', 23);
        // $replacement === $replacement2
        $replacement2 = FunctionMocker::replace('SomeClass::methodTwo', 23);

        $replacement->methodOne();
        $replacement->methodTwo();

        $replacement->wasCalledOnce('methodOne');
        $replacement->wasCalledOnce('methodTwo');
    }
}
```

An alternative and more fluid API allows rewriting the assertions above in a way that's more similar to the one used by [prophecy](https://github.com/phpspec/prophecy "phpspec/prophecy · GitHub"):

```php
// file SomeClassTest.php   

use tad\FunctionMocker\FunctionMocker;

class SomeClassTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function returns_the_same_replacement_object(){
        // replace both class instance methods to return 23
        $mock = FunctionMocker::replace('SomeClass)
            ->methodOne()
            ->methodTwo();
        $replacement = $mock->get(); // think of $mock->reveal()

        $replacement->methodOne();
        $replacement->methodTwo();

        $mock->verify()->methodOne()->wasCalledOnce();
        $mock->verify()->methodTwo()->wasCalledOnce();
    }
}
```


#### Batch replacing instance methods
It's possible to batch replace instances using the same syntax used for batch function and static method replacement.  
Given the `SomeClass` above:

```php
public function testBatchInstanceMethodReplacement(){
    $methods = ['SomeClass::methodOne', 'SomeClass::methodTwo'];
    // replace both class instance methods to return 23
    $replacements = FunctionMocker::replace($methods, 23);

    $replacement[0]->methodOne();
    $replacement[1]->methodTwo();

    $replacement[0]->wasCalledOnce('methodOne');
    $replacement[1]->wasCalledOnce('methodTwo');
}
```

## Methods
Beside the methods defined as part of a [PHPUnit](http://phpunit.de/) mock object interface (see [here](https://phpunit.de/manual/3.7/en/test-doubles.html)), available only when replacing instance methods, the function mocker will extend the replaced functions and methods with the following methods:

* `wasCalledTimes(int $times [, string $methodName])` - will assert a PHPUnit assertion if the function or static method was called `$times` times; the `$times` parameter can come using the times syntax below.
* `wasCalledOnce([string $methodName])` - will assert a PHPUnit assertion if the function or static method was called once.
* `wasNotCalled([string $methodName])` - will assert a PHPUnit assertion if the function or static method was not called.
* `wasCalledWithTimes(array $args, int $times[, string $methodName])` - will assert a PHPUnit assertion if the function or static method was called with `$args` arguments `$times` times; the `$times` parameter can come using the times syntax below; the `$args` parameter can be any combination of primitive values and PHPUnit constraints like `[23, Test::isInstanceOf('SomeClass')]`.
* `wasCalledWithOnce(array $args[, string $methodName])` - will assert a PHPUnit assertion if the function or static method was called with `$args` arguments once; the `$args` parameter can be any combination of primitive values and PHPUnit constraints like `[23, Test::isInstanceOf('SomeClass')]`.
* `wasNotCalledWith(array $args[, string $methodName])` - will assert a PHPUnit assertion if the function or static method was not called with `$args` arguments; the `$args` parameter can be any combination of primitive values and PHPUnit constraints like `[23, Test::isInstanceOf('SomeClass')]`.

>The method name is needed to verify calls on replaced instance methods!

### Times
When specifying the number of times a function or method should have been called a flexible syntax is available; in its most basic form can be expressed in numbers
    
```php  
// the function should have have been called exactly 2 times
$function->wasCalledTimes(2);
```

but the usage of strings makes the check less cumbersome using the comparator syntax used in PHP

```php
// the function should have been called at least 2 times
$function->wasCalledTimes('>=2');
```

available comparators are `>n`, `<n`, `>=n`, `<=n`, `==n` (same as inserting a number), `!n`.

## Sugar methods
Function Mocker packs some sugar methods to make my testing life easier. The result of any of these methods can be achieved using alternative code but I've implemented those to speed things up a bit.

### Test methods
Function Mocker wraps a `PHPUnit_Framework_TestCase` to allow the calling of test methods normally called on `$this` to be statically called on the `FunctionMocker`
class or any of its aliases. A test method can be writte like this

```php
use tad\FunctionMocker\FunctionMocker as Test;

class SomeTest extends \PHPUnit_Framework_TestCase {

    public function test_true() {
        $this->assertTrue(true);
    }

    public function test_wrapped_true_work_the_same() {
        Test::assertTrue(true);
    }

}
```

Being a mere wrapping the test case to be used can be set using the `setTestCase` static method in the test case `setUp` method

```php  
public function setUp() {
    FunctionMocker::setTestCase($this);
}
```

and any method specific to the test case will be available as a static method of the `tad\FunctionMocker\FunctionMocker` class.  
Beside methods defined by the wrapped test case any method defined by the `PHPUnit_Framework_TestCase` class is available for autocompletion to comment reading IDEs like [PhpStorm](http://www.jetbrains.com/phpstorm/) or [Sublime Text](http://www.sublimetext.com/).

### Replacing a  global
Allows replacing a global with a mock object and restore it after the test. Best used to replace/set globally shared instances of objects to mock; e.g.: 

```php 
FunctionMocker::replaceGlobal('wpdb', 'wpdb::get_row', $rowData);

// this will access $wpdb->get_row()
$post = get_latest_post();

// verify
$this->assertEquals(...);
```

is the same as writing

```php 
// prepare
$mockWpdb = FunctionMocker::replace('wpdb::get_row', $rowData);
$prevWpdb = isset($GLOBALS['wpdb']) ? $GLOBALS['wpdb'] : null;
$GLOBALS['wpdb'] = $mockWpdb;

// this will access $wpdb->get_row()
$post = get_latest_post();

// verify
$this->assertEquals(...);

// restore state
$GLOBALS['wpdb'] = $prevWpdb;
```

### Setting a global
Allows replacing/setting a global value and restore it's state after the test.
  
```php  
FunctionMocker::setGlobal('switchingToTheme', 'foo');
$do_action = FunctionMocker::replace('do_action');

// exercitate
call_switch_theme_actions();

$do_action->wasCalledWithOnce(['before_switch_to_theme_foo', ])
```

same as writing

```php
$prev = isset($GLOBALS['switchingToTheme']) ? $GLOBALS['switchingToTheme'] : null;
$GLOBALS['switchingToTheme'] = 'foo';
$do_action = FunctionMocker::replace('do_action');

// exercitate
call_switch_theme_actions();

// verify 
$do_action->wasCalledWithOnce(['before_switch_to_theme_foo', ])

// restore state
$GLOBALS['switchingToTheme'] = $prev;
```