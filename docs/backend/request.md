# Request Object

SquareOne provides a Request helper object in order to allow easy access to common request (or server) related values, such as headers, input values, and URL/path information.

The Request class is located in `core/src/Request/Request.php`.

## Usage

The Request object is registered in the Pimple Container as `request`. This can be made available to your classes via Direct Injection
in your Service Provider.
 

```php
$container['my_cool_class'] = function() use ( Container $container ) {
    return new My_Cool_Class( $container['request'] );
};


class My_Cool_Class {

    protected $request;

    public function __construct( Request $request ) {
        $this->request = $request;
    }

}
```
 
Once injected, the Request object will automatically contain the various values relating to the current request. For instance,
to access the `Content-Type` header, you could use:

```php
$content_type = $this->request->header('Content-Type');
```
 
The Request Object is also available via a Facade for usage in classes in which you don't have control
over the Constructor (such as a Controller) in order to inject the class as you normally would. 

```php
class My_Controller extends Twig_Template {
    
    public function get_data() {
        $data = parent::get_data();
    
        $data['content-type'] = Request::header('Content-Type');
        
        return $data;
    }
}
```

## Methods

### query() 

```php
@return \WP_Query
```

Get the current `\WP_Query` global object. Note that this always grabs the `global $wp_query` at the time of calling in 
order to provide the most-up-to-date version of the object, so it can be used just like `global $wp_query` can within hooks.

```php
$query = $this->request->query();
$posts = $query->found_posts; 
```

### headers() 

```php
@return array
```

Get all of the headers for this request.

```php
$headers = $this->request->headers();

echo $headers['Content-Type'];
```

### header( $key )

```php
@param  string $key
@return string
```

Get the header value by key.

```php
$content_type = $this->request->header('Content-Type');
```

### input( $key )

```php
@param  string $key
@return mixed 
```

Get the input from the Request by key. Automatically detects the method (GET, POST, JSON body) and returns the value from 
the correct method values.

```php
$foobar = $this->request->input('foobar');
```

### all()

```php
@return array
```

Get all input values from the Request. Automatically detects method and pulls in the values from there. If method is _not_ GET
and the request also has query parameters, returns both the method input _and_ the query parameters.

```php
$all_input = $this->request->all();
```

### only( $keys )

```php
@param  array $keys
@return array
```

Return _only_ input values matching the provided keys. Returns an array containing only those keys which existed (including empty values).
Will not return values for non-existent keys.

```php
$keys = [ 'foo', 'bar', 'bash' ];

$values = $this->request->only( $keys );
```

### except( $keys )

```php
@param  array $keys
@return array
```

Return all input values _except_ those matching the provided keys. Returns an empty array if no other values exist beyond 
the provided keys.

```php
$keys = [ 'foo', 'bar' ];

$values = $this->request->except( $keys );
```

### has( $key )

```php
@param  string $key
@return bool
```

Determine whether an input matching the provided key exists in the Request. Will return `true` if input key exists even if it is empty.

```php
$has_foobar = $this->request->has('foobar');

if ( $has_foobar ) {
    // do thing.
}
```

### filled( $key )

```php
@param  string $key
@return bool
```

Determine whether an input matching the provided key exists and is non-empty. Will return `true` if value is bool or 0, but 
`false` for any empty string or null value.

```php
$foobar_filled = $this->request->filled('foobar');

if ( $foobar_filled ) {
    // do thing.
}
```

### path( $include_params = false )

```php
@param  bool $include_params
@return string
```

Get the current Request path. If `$include_params` is set to `true`, also include any Query Params in the path.

```php
// URL is http://foobar.com/page/here?foo=bar

$path = $this->>request->path();
echo $path;

// /page/here

$path_with_params = $this->request->path( true );
echo $path_with_params;

// /page/here?foo=bar
```

### url()

```php
@return string
```

Get the current Request URL. Does not contain path or any Query Parameters.

```php
// URL is http://foobar.com/page/here?foo=bar

$url = $this->>request->url();
echo $url;

// http://foobar.com
```

### full_url()

```php
@return string
```

Get the full current Request URL. Includes both the path and any Query Parameters.

```php
// is http://foobar.com/page/here?foo=bar

$full_url = $this->>request->full_url();
echo $full_url;

// http://foobar.com/page/here?foo=bar
```

### is( $path )

```php
@param  string $path
@return bool
```

Determine if the current Request Path matches the given pattern. Wildcards (*) can be used.

```php

// URL is http://foobar.com/page/here?foo=bar

$is_page = $this->>request->is('page/here');

// true

$is_page = $this->request->is('page/*');

// true

$is_page = $this->request->is('page');

// false
```