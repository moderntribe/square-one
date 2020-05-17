# Post Types

## Registration

The registration of a post type takes place in the core/src/Post_Types directory. Create a subdirectory
for each post type. The steps to registering a new post type are:

### The Post Type Object
Create a class extending `\Tribe\Libs\Post_Type\Post_Object`. It should have a value set for the
`NAME` constant. This class is responsible for announcing the existence of the post type. See
`\Tribe\Project\Post_Types\Sample\Sample` included in this package for an example.

If this is a 3rd-party post type, you can stop. This class is used to reference
that post type, but nothing else needs to be done here.

This may be a convenient location to declare other methods appropriate to that post type.

### The Post Type Configuration
A class extending `\Tribe\Libs\Post_Type\Post_Type_Config` is responsible for configuring
the post type. The two methods to define are `get_args()` and `get_labels()`. The `$post_type`
property should be set to point to the post type's name. See
`\Tribe\Project\Post_Types\Sample\Config` included in this package for an example.
   
#### Additional Configuration Options

Post type registration passes through the [Extended CPTs library](https://github.com/johnbillion/extended-cpts).
Any options available through that library may be used here.

See: https://github.com/johnbillion/extended-cpts/wiki/Other-admin-parameters 
```php
public function get_args() {
	return [
		'hierarchical'     => false,
		'enter_title_here' => __( 'Sample', 'tribe' ),
		'map_meta_cap'     => true,
		'supports'         => [ 'title', 'editor' ],
		'capability_type'  => 'post', // to use default WP caps
		'dashboard_glance' => true, // Show post count in At a Glance dashboard metabox.
		'quick_edit'       => true // Allow quick edit.
		
	];
}
```

Posts view Column handling is another non-obvious feature.
See: https://github.com/johnbillion/extended-cpts/wiki/Admin-columns
```php
public function get_args() {
	return [
		'hierarchical'     => false,
		'enter_title_here' => __( 'Sample', 'tribe' ),
		'map_meta_cap'     => true,
		'supports'         => [ 'title', 'editor' ],
		'capability_type'  => 'post', // to use default WP caps
		'admin_cols' => [
        		// A featured image column:
        		'featured_image' => [
        			'title'          => 'Illustration',
        			'featured_image' => 'thumbnail'
        		],
        		// A meta field column:
        		'published' => [
        			'title'       => 'Published',
        			'meta_key'    => 'published_date',
        			'date_format' => 'd/m/Y'
        		],
        		// A taxonomy terms column:
        		'genre' => [
        			'taxonomy' => 'genre'
        		],
        		// User callable:
        		'callable' => [
        		    'title'    => 'Called stuff',
        		    'function' => [ $this, 'callable' ],
        		],
        	],
	];
}
```

See also: https://github.com/johnbillion/extended-cpts/wiki/Admin-filters

### The Post Type Subscriber

A class extending `\Tribe\Libs\Post_Type\Post_Type_Subscriber` is responsible
for hooking the post type into WordPress for registration. In the simplest case,
it just needs to set a reference to the aforementioned `Config` class. See
`\Tribe\Project\Post_Types\Sample\Subscriber` included in this package for an example.

In many cases, you will have other classes related to the post type that will also
be hooked into WordPress using the same subscriber. To do so, extend the `register()`
method to call your additional methods.

```php
public function register(): void {
  parent::register();
  $this->do_more_registration_things();
}
```

Add your subscriber to the `$subscribers` array in `\Tribe\Project\Core` to finish the process
of registering the post type. Add it under the comment that reads "// our post types".

## Generator

Tribe Libs comes with a [post type generator CLI command](https://github.com/moderntribe/tribe-libs/tree/master/src/Generators).
It will create all of the required files, placing them in the correct directory,
and will update `\Tribe\Project\Core` to reference the subscriber.

