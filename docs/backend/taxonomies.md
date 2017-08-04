### === Custom Taxonomies ===

Creating a custom taxonomy is very similar to custom post types.

You start by creating a sub directory named for your custom taxonomy within the core/src/Taxonomies directory.

Using a **Feed_Tag** taxonomy as an example you would see:

- /wp-content/plugins/core/src/Taxonomies/Feed_Tag/Config.php
- /wp-content/plugins/core/src/Taxonomies/Feed_Tag/Feed_Tag.php

Both files of a post type should be namespaced to that particular Taxonomy.

```namespace Tribe\Project\Taxonomies\Feed_Tag;```

**Config.php**

The Config.php class sets up the normal configuration arguments needed in a taxonomy to be registered.  

The class should extend Taxonomy_Config

```php
namespace Tribe\Project\Taxonomies\Feed_Tag;

use Tribe\Libs\Taxonomy\Taxonomy_Config;

class Config extends Taxonomy_Config {

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

**Feed_Tag.php**

The {{Taxonomy_Name}}.php file in our example case, Feed_Tag.php, must define the constant name of the custom taxonomy.  At it's simplest, it will nearly always look like:

```php
namespace Tribe\Project\Taxonomies\Feed_Tag;

class Feed_Tag {
	const NAME = 'feed_tag';
}
```

**Registering A Taxonomy**

To register a taxonomy they must have a service provider.  See [Service Provides](service-providers.md) section for more information.

The new taxonomy's service provider should be located in the Service_Providers/Taxonomies directory.  For our example:

 - /wp-content/plugins/core/src/Service_Providers/Taxonomies/Feed_Tag_Service_Provider.php

The Taxonomy_Service_Provider class handles registering the taxonomy so here you simply need to extend it and define the protected properties which are constants from your Taxonomy class and the post types the taxonomy relates to:

- protected $taxonomy_class
- protected $config_class
- protected $post_types which is an array of post types

```php
namespace Tribe\Project\Service_Providers\Taxonomies;

use Tribe\Project\Post_Types\Feed_Story\Feed_Story;
use Tribe\Project\Taxonomies\Feed_Tag;

class Feed_Tag_Service_Provider extends Taxonomy_Service_Provider {
	protected $taxonomy_class = Feed_Tag\Feed_Tag::class;
	protected $config_class   = Feed_Tag\Config::class;
	protected $post_types     = [
		Feed_Story::NAME,
	];
}
```

The final step in making your taxonomy available is registering it's container in core/src/Core.php

```php
private function load_taxonomy_providers() {
	$this->container->register( new Feed_Tag_Service_Provider() );
```