# Args

A library to make argument checking less of a chore.

## Missing type hinting
PHP will not allow [type hinting](http://php.net/manual/en/language.oop5.typehinting.php) scalar values in functions and methods arguments forcing cycles like this to be added in each function body:

    if (!is_string($s)){
        throw ...
    }
    if(strlen($s) > 20 || strlen($s) < 10){
        throw ...
    }
    if(preg_match($this->name_pattern, $s) === false){
        throw ...
    }

The library, still a work in progress aims at making argument check more granular, tested and [DRYer](http://en.wikipedia.org/wiki/Don't_repeat_yourself) ') by introducing a fluent interface.  
The check above could become

    Arg::_($s)->is_string()->length(10, 20)->match($this->name_pattern);

Some array-related functions are there too:

    Arg::_($a)->count(6, 10)->contains('value1', 'value2')->has_key('key_one');

Each method will throw an Exception if the condition is not satisfied. In the array example if array is `['some', 23]` then an exception will be thrown with the message:

    Array must contain at least 6 elements!

## Installation
Download the class and add it to your project or use [Composer](https://getcomposer.org/) like
    
    require: {
        "lucatume/args": "~0.1"
    }

## Methods
Each call to the class should begin using the static method `_()` and passing it the value to check and, optionally, the name that value will be referred to in exceptions

    Arg::_($value, 'amount');

To actually check the argument against a condition use the `assert()` method

    Arg::_($value)->assert($value > 13);

While that's the base this syntax is best used in special cases the clas doesn't cover and the convenient methods provided by the class should be used.  
Each method is defined in a positive logic: nothing will happen if the argument matches the expectation, an exception will be thrown otherwise.

* `is_bool()` - checks if value is a boolean, same as `is_bool()` method
* `is_scalar()` - checks if value is a scalar, same as `is_scalar()` method
* `is_string()` - checks if value is a string, same as `is_string()` method
* `is_numeric()` - checks if value is a numeric, same as `is_numeric()` method
* `is_int()` - checks if value is a int, same as `is_int()` method
* `is_double()` - checks if value is a double, same as `is_double()` method
* `is_float()` - checks if value is a float, same as `is_float()` method
* `is_null()` - checks if value is null, same as `is_null()` method
* `is_resource()` - checks if value is a resource, same as `is_resource()` method
* `is_array()` - checks if value is an array, same as `is_array()` method
* `is_associative_array()` - checks if value is an associative array
* `is_object()` - checks if value is an object, same as `is_object()` method
* `else_throw($exception)` - throws the specified exception if the checks are failing; `$exception` can be an object instance or a class name. If the class name ends in `Exception` then that part of the class name can be omitted (e.g. "NotGoodException" to "NotGood")

### Scalar methods
If an argument is a scalar then additional check methods are available:

* `at_least($value)` - checks if the argument is >= the value
* `at_most($value)` - checks if the argument is <= the value
* `greater_than($value)` - checks if the argument is > the value
* `less_than($value)` - checks if the argument is < the value

## String methods
If an argument is a string then additional methods are available

* `length($min, [$max])` - checks if the argument is at least `$min`  long and, optionally, at most `$max` chars long.
* `match($pattern)` - checks if the argument matches the given regex patterns

### Array methods
If an argument is an array then additional methods are available

* `count($min, [$max])` - checks if the argument contains at least `$min`  elements and, optionally, that it contains at most `$max` elements.
* `has_structure($structure)` - checks if the array has exactly the provided strcture, e.g.  
    
    $structure = array(
        'key1' => null,
        'key2' => null,
        'key3' => array(
            'subKey1' => null,
            'subKey1' => null 
        )
    );

    $structure = array(
        'key1' => 'some',
        'key2' => 'foo',
        'key3' => array(
            'subKey1' => 12,
            'subKey1' => 23 
        )
    );

    Arg::_($arr)->has_structure($structure);

* `extends_structure($structure)` - checks if the array extends the provided strcture
* `defaults($defaults)` - does not perform a check but merges the argument array with a default set
* `contains($value [,$value] [,$value2])` - checks if the argument contains the specified value(s)
* `has_key($value [,$value] [,$value2])` - checks if the argument contains the specified key(s)

### Object methods
* `is_set($property [, $property][, $property] )` - checks if one ore more specified properties are set