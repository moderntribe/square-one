# Post Types

The general constructing of a post type takes place in the core/src/Post_Types directory and requires two files within their own directory.

Using a hypothetical **Sample** post type as an example you would see:

- /wp-content/plugins/core/src/Post_Types/Sample/Config.php
- /wp-content/plugins/core/src/Post_Types/Sample/Sample.php

Both files of a post type should be namespaced to that particular CPT.

```namespace Tribe\Project\Post_Types\Sample;```

### Config.php

The Config class sets up the normal configuration arguments needed in a custom post type to be registered.  The class should extend Post_Type_Config, which contains the logic for registering the post type with WordPress.

```php
use Tribe\Libs\Post_Type\Post_Type_Config;

class Config extends Post_Type_Config {
	public function get_args() {
		return [
			'hierarchical'     => false,
			'enter_title_here' => __( 'Sample', 'tribe' ),
			'map_meta_cap'     => true,
			'supports'         => [ 'title', 'editor' ],
			//'capability_type'  => $this->post_type(), // for custom caps
			'capability_type'  => 'post', // to use default WP caps
		];
	}

	public function get_labels() {
		return [
			'singular' => __( 'Sample', 'tribe' ),
			'plural'   => __( 'Samples', 'tribe' ),
			'slug'     => __( 'samples', 'tribe' ),
		];
	}

}
```

### Sample.php

The {{Post_Type_Name}}.php file in our example case, Sample.php, must contain a Post_Object child class and define the constant name of the post type.  At it's simplest it will look like:

```php
namespace Tribe\Project\Post_Types\Sample;

use Tribe\Libs\Post_Type\Post_Object;

class Sample extends Post_Object {
	const NAME = 'sample_post_type';
}
```

## Registering A Post Type

To register a post type they must have a subscriber.  See the [Subscribers](subscribers.md) section for more information.

The new post type's subscriber should be located in the post type's directory.  For our example:

 - /wp-content/plugins/core/src/Post_Types/Sample/Subscriber.php

The Subscriber class handles registering the post type so here you simply need to extend it and define the protected properties:

- protected $config_class

```php
namespace Tribe\Project\Post_Types\Sample;

class Subscriber extends Post_Type_Subscriber {
	protected $config_class = Config::class;
}
```

The final step in making your post type available is registering it's subscriber in core/src/Core.php.
Add `Post_Types\Sample\Subscriber::class` to the `$subscribers` array.

## 3rd-Party Post Types

Some post types are registered by 3rd-party plugins, or by WordPress core (e.g., Page and Post).

3rd-party post types do not require a Config file, but should still have a Post_Object subclass.
This allows us to use our post meta framework for post types that we have not directly registered.

**Additional Configuration Options**

Some simple flags built into extended post types. ( see: https://github.com/johnbillion/extended-cpts/wiki/Other-admin-parameters ) 
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

Posts view Column handling is another non-obvious feature. ( see: https://github.com/johnbillion/extended-cpts/wiki/Admin-columns )
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

You should also see: https://github.com/johnbillion/extended-cpts/wiki/Admin-filters
