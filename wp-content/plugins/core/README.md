#  === [1] Back End ===


Welcome to [1]! The core plugin will be where the majority of back end development takes place.  This is a living document so you may check the square-one repository for any new information if you are joining this project after inception.


##  === Ground Rules ===


* Let's start with the easiest first: write good, clean, well structured and commented code!
* Be nice and comment your code. You should get a good idea of guidelines for doing so within the various files and languages in [1].
* If you have questions about anything at all always ask!
* Do your best to stick to the conventions and rules laid out in [1], and if you have ideas to improve upon [1] let us know.


##  === Post Types ===

The general constructing of a post type takes place in the core/src/Post_Types directory and requires two files within their own directory.

Using the included **Sample** post type as an example you will see:

- /wp-content/plugins/core/src/Post_Types/Sample/Config.php
- /wp-content/plugins/core/src/Post_Types/Sample/Sample.php

Both files of a post type should be namespaced to that particular CPT.

```namespace Tribe\Project\Post_Types\Sample;```

**Config.php**

The Config.php class sets up the normal configuration arguments needed in a custom post type to be registered.  The class should extend Post_Type_Config

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

**Sample.php**

The {{Post_Type_Name}}.php file in our example case, Sample.php, must contain a Post_Type child class and define the constant name of the post type.  At it's simplest it will look like:

```php
namespace Tribe\Project\Post_Types\Sample;

use Tribe\Libs\Post_Type\Post_Object;

class Sample extends Post_Object {
	const NAME = 'sample_post_type';
}
```

**Registering A Post Type**

To register a post type they must have a service provider.  See [Service Provides](#) section for more information.

The new post type's service provider should be located in the Service_Providers/Post_Types directory.  For our example:

 - /wp-content/plugins/core/src/Service_Providers/Post_Types/Sample_Post_Type_Service_Provider.php

**Note:** *Typically naming convention outside of this Sample Post Type would be Service_Providers/Post_Types/{{Post_Type_Name}}_Service_Provider.php*

The Post_Type_Service_Provider class handles registering the post type so here you simply need to extend it and define the protected properties:

- protected $post_type_class
- protected $config_class

```php
use Tribe\Project\Post_Types\Sample;

class Sample_Post_Type_Service_Provider extends Post_Type_Service_Provider {
	protected $post_type_class = Sample\Sample::class;
	protected $config_class = Sample\Config::class;
}
```

The final step in making your post type available is registering it's container in core/src/Core.php

```php
private function load_post_type_providers() {
	$this->container->register( new Sample_Post_Type_Service_Provider() );
```

# === Post Meta & ACF ===