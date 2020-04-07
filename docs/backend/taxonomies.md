# Custom Taxonomies

Creating a custom taxonomy is very similar to custom post types.

You start by creating a sub directory named for your custom taxonomy within the core/src/Taxonomies directory.

Using a **Feed_Tag** taxonomy as an example you would see:

- /wp-content/plugins/core/src/Taxonomies/Feed_Tag/Config.php
- /wp-content/plugins/core/src/Taxonomies/Feed_Tag/Feed_Tag.php

Both files of a post type should be namespaced to that particular Taxonomy.

```namespace Tribe\Project\Taxonomies\Feed_Tag;```

### Config.php

The Config.php class sets up the normal configuration arguments needed in a taxonomy to be registered.  

The class should extend Taxonomy_Config

```php
namespace Tribe\Project\Taxonomies\Feed_Tag;

use Tribe\Libs\Taxonomy\Taxonomy_Config;

class Config extends Taxonomy_Config {
	protected $taxonomy = Feed_Tag::NAME;
	protected $post_types = [
		Feed_Story::NAME,
	];

	public function get_args() {
		return [
			'hierarchical' => false,
			'public'       => false,
			'show_ui'      => true,
			'capabilities' => [
				'assign_terms' => 'assign_feed_tags',
			],
		];
	}

	public function get_labels() {
		return [
			'singular' => __( 'Tag', 'tribe' ),
			'plural'   => __( 'Tags', 'tribe' ),
			'slug'     => __( 'feeds/tags', 'tribe' ),
		];
	}
}
```

### Feed_Tag.php

The {{Taxonomy_Name}}.php file in our example case, Feed_Tag.php, must define the constant name of the custom taxonomy.  At it's simplest, it will nearly always look like:

```php
namespace Tribe\Project\Taxonomies\Feed_Tag;

use Tribe\Libs\Taxonomy\Term_Object;

class Feed_Tag extends Term_Object {
	const NAME = 'feed_tag';
}
```

## Registering A Taxonomy

To register a taxonomy they must have a subscriber.  See the [Subscribers](subscribers.md) section for more information.

The new taxonomy's subscriber should be located in the taxonomy's directory.  For our example:

 - /wp-content/plugins/core/src/Taxonomies/Feed_Tag/Subscriber.php

The `Taxonomy_Subscriber` class handles registering the taxonomy so here you simply need to extend it and define the protected properties which points to the configuration file:

- protected $config_class

```php
namespace Tribe\Project\Taxonomies\Feed_Tag;

use Tribe\Project\Post_Types\Feed_Story\Feed_Story;

class Subscriber extends Taxonomy_Subscriber {
	protected $config_class   = Config::class;
}
```

The final step in making your taxonomy available is registering it's subscriber in core/src/Core.php.
Add `Taxonomies\Feed_Tag\Subscriber::class` to the `$subscribers` array.
