# Custom Taxonomies

Custom taxonomies are handled very similarly to [custom post types](./post-types.md).

## Registration

The registration of a taxonomy takes place in the core/src/Taxonomies directory. Create a subdirectory
for each taxonomy. The steps to registering a new taxonomy are:

### The Taxonomy Object
Create a class extending `\Tribe\Libs\Taxonomy\Term_Object`. It should have a value set for the
`NAME` constant. This class is responsible for announcing the existence of the taxonomy. See
`\Tribe\Project\Taxonomies\Example\Example` included in this package for an example.

If this is a 3rd-party taxonomy, you can stop. This class is used to reference
that taxonomy, but nothing else needs to be done here.

This may be a convenient location to declare other methods appropriate to that taxonomy.

### The Taxonomy Configuration
A class extending `\Tribe\Libs\Taxonomy\Taxonomy_Config` is responsible for configuring
the post type. The two methods to define are `get_args()` and `get_labels()`. Set the
`$taxonomy` property should be set with the value of the taxonomy's name. The `$post_types`
property should be set to an array containing the post types to which this taxonomy will
be applied.
 
See `\Tribe\Project\Taxonomies\Example\Config` included in this package for an example.
   
#### Additional Configuration Options

Taxonomy registration passes through the [Extended CPTs library](https://github.com/johnbillion/extended-cpts).
Any options available through that library may be used here.

See: https://github.com/johnbillion/extended-cpts/wiki/Registering-taxonomies

### The Taxonomy Subscriber

A class extending `\Tribe\Libs\Taxonomy\Taxonomy_Subscriber` is responsible
for hooking the taxonomy into WordPress for registration. In the simplest case,
it just needs to set a reference to the aforementioned `Config` class. See
`\Tribe\Project\Taxonomies\Example\Subscriber` included in this package for an example.

In many cases, you will have other classes related to the taxonomy that will also
be hooked into WordPress using the same subscriber. To do so, extend the `register()`
method to call your additional methods.

```php
public function register(): void {
  parent::register();
  $this->do_more_registration_things();
}
```

Add your subscriber to the `$subscribers` array in `\Tribe\Project\Core` to finish the process
of registering the taxonomy. Add it under the comment that reads "// our taxonomies".

## Generator

Tribe Libs comes with a [taxonomy generator CLI command](https://github.com/moderntribe/tribe-libs/tree/master/src/Generators).
It will create all of the required files, placing them in the correct directory,
and will update `\Tribe\Project\Core` to reference the subscriber.
