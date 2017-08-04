##  === Post & Taxonomy Meta ===

Post Meta & Taxonomy Meta are nearly identical in how they are handled.  This will cover creating post meta data, see an example of taxonomy meta at the end.

---

Post Meta is managed through the Advanced Custom Fields Pro plugin.  Post Meta classes should be organized in the core/src/Post_Meta directory.

Since meta fields can be generated for multiple post types the post meta class and file name should be related to what the meta represents.

For example, the Hide_Title post meta class, which contains a true/false checkbox, can be used for articles, posts, pages or any other post type.

---

### Extending ACF

Post_Meta classes will extend ACF\ACF_Meta_Group

```class Sample_Meta extends ACF\ACF_Meta_Group { }```

Use constants to name your class and any keys you will be using for meta fields.

```php
class Sample_Meta extends ACF\ACF_Meta_Group {
    /**
     * NAME represents our sample meta class
     */
    const NAME = 'sample_meta';
    
    /**
     * Here we define keys for two fields, a text field and a date field.  
     * The terms text/date are not necessary when naming your fields and are 
     * just used for this example;
     */
    const SAMPLE_TEXT = 'sample_text';
    const SAMPLE_DATE = 'sample_date';
}
```

You will need to include get_keys() and get_value() functions.  The get_value() function allows for running logic operations dependent on the key being passed in.

```php
public function get_keys() {
    return [
        self::SAMPLE_TEXT,
        self::SAMPLE_DATE,
    ];
}

public function get_value( $post_id, $key ) {
    switch ( $key ) {
        case self::SAMPLE_DATE :
            $date = get_field( $key, $post_id );
            if ( $date < current_time( 'today' ) {
                return 'expired';
            }
            return $date;
        default:
            return get_field( $key, $post_id );
    }
}
```

Now it's **important to include your *protected* get_group_config() function** which will generate your ACF group and any fields added to it.

Fields should be created in separate functions and returned to this function as a parameter for add_field().

All ACF properties can be set within these functions.  See here [ACF: Register Fields Via PHP](https://www.advancedcustomfields.com/resources/register-fields-via-php/)

```php
protected function get_group_config() {

    $group = new ACF\Group( static::NAME );
    $group->set( 'title', __( 'Sample Meta Fields', 'tribe' ) );
    $group->set( 'label_placement', 'left' );
    $group->set( 'position', 'normal' );
    /**
     * $this->post_types is passed in through the service 
     * provider and set by the parent class
     */
    $group->set_post_types( $this->post_types );

    $group->add_field( $this->get_sample_text_field() );
    $group->add_field( $this->get_sample_date_field() );

    return $group->get_attributes();
}

private function get_sample_text_field() {
    $field = new ACF\Field( self::NAME . '_' . self::SAMPLE_TEXT );
    $field->set_attributes( [
        'label' => __( 'Insert Sample Text', 'tribe' ),
        'message' => '',
        'name'    => self::SAMPLE_TEXT,
        'type'    => 'text',
    ] );

    return $field;
}

private function get_sample_text_field() {
    $field = new ACF\Field( self::NAME . '_' . self::SAMPLE_DATE );
    $field->set_attributes( [
        'label' => __( 'Select a Date', 'tribe' ),
        'message' => '',
        'name'    => self::SAMPLE_DATE,
        'type'    => 'date_picker',
    ] );

    return $field;
}
```

---

### Registering In Service Provider

The final step in make your meta active is to register it in the Post_Meta_Service_Provider.php file located in core/src/Service_Providers/ directory

Create a private function in the service provider class and assign your meta to a properly namespaced container

Be sure to add use for your post meta and needed post types classes for cleaner code:
```php
use Tribe\Project\Post_Meta\Sample_Meta;
use Tribe\Project\Post_Types\Article\Article;
use Tribe\Project\Post_Types\Page\Page;
```

Then in the body of the class add the function and define it's post types as the parameter

```php
private function sample_meta( Container $container ) {
    $container[ 'post_meta.sample_meta' ] = function ( Container $container ) {
        return new Sample_Meta( [
            Page::NAME,
            Article::NAME,
        ] );
    };
}
```

If you need to run add any actions or filters related to your meta do so inside this function after the container is defined

```php
private function sample_meta( Container $container ) {
    $container[ 'post_meta.sample_meta' ] = function ( Container $container ) {
        return new Sample_Meta( [
            Page::NAME,
            Article::NAME,
        ] );
    };

    add_filter( 'acf/render_field/type=text', function ( $field ) use ( $container ) {
        return $container[ 'post_meta.sample_meta' ]->do_stuff_to_text_meta( $field );
    }, 11, 1 );
}
```

Make sure your sample_meta function is called at the top of the Post_Meta_Service_Provider class in the register function:

```php

class Post_Meta_Service_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$this->sample_meta( $container );
```

Finally, add the object to the array passed to `$container[ 'post_meta.collection_repo' ]`.

```php
$container[ 'post_meta.collection_repo' ] = function ( Container $container ) {
	return new Meta_Repository( [
		$container[ 'post_meta.sample_meta' ],
	] );
};
```

### Retrieving Meta

To access data stored in post meta, get an instance of your Post_Object, and call its get_meta() method.

```php
$resource = Resource::factory( $post_id );
$keywords = $resource->get_meta( Search::FEATURED_KEYWORDS );
```

The aforementioned `Meta_Repository` knows which meta object will handle that key (based on its `get_keys()` method) and route the request appropriately.


---

## Taxonomy Meta

```php

namespace Tribe\Project\Taxonomy_Meta;

use Tribe\Libs\ACF;

class Sample_Tax_Meta extends ACF\ACF_Meta_Group {
	const NAME = 'sample_tax_meta';

	const URL     = 'sample_url';
	const FORMAT  = 'sample_format';


	const FORMAT_NEWS = 'news';
	const FORMAT_RSS  = 'rss';

	protected $taxonomies = [];

	/**
	 * Meta_Group constructor.
	 *
	 * @param array $post_types The post types the meta group applies to
	 */
	public function __construct( array $taxonomies ) {
		$this->taxonomies = $taxonomies;
		parent::__construct( [] );
	}

	/**
	 * @return array The meta keys that this field will handle.
	 *               While these will probably directly correspond
	 *               to meta keys in the database, there is no
	 *               guaranteed, as the key may correspond to
	 *               a computed/aggregate value.
	 */
	public function get_keys() {
		return [
			self::URL,
			self::FORMAT,
		];
	}

	/**
	 * Base implementation that uses get_field() for all registered keys
	 *
	 * If you have calculated/aggregated keys that don't match directly
	 * to a meta, you'll need to override this method on the child class.
	 *
	 * @param int    $term_id
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function get_value( $term_id, $key ) {
		if ( is_numeric( $term_id ) ) {
			$term_id = 'term_' . $term_id;
		}
		switch ( $key ) {
			default:
				return get_field( $key, $term_id );
		}
	}

	/**
	 * @return array The ACF config array for the field group
	 */
	protected function get_group_config() {
		$group = new ACF\Group( static::NAME );
		$group->set( 'title', __( 'Sample Tax Options', 'tribe' ) );
		$locations = [];
		foreach ( $this->get_taxonomies() as $taxonomy ) {
			$locations[] = [
				[
					'param'    => 'taxonomy',
					'operator' => '==',
					'value'    => $taxonomy,
				],
			];
		}
		$group->set( 'location', $locations );

		$group->add_field( $this->get_url_field() );
		$group->add_field( $this->get_format_field() );

		return $group->get_attributes();
	}

	private function get_url_field() {
		$field = new ACF\Field( self::NAME . '_' . self::URL );
		$field->set_attributes( [
			'label' => __( 'URL', 'tribe' ),
			'name'  => static::URL,
			'type'  => 'url',
		] );

		return $field;
	}

	private function get_format_field() {
		$field = new ACF\Field( self::NAME . '_' . self::FORMAT );
		$field->set_attributes( [
			'label'         => __( 'Format Type', 'tribe' ),
			'name'          => static::FORMAT,
			'type'          => 'radio',
			'layout'        => 'horizontal',
			'choices'       => [
				self::FORMAT_NEWS => __( 'New JSON', 'tribe' ),
				self::FORMAT_RSS      => __( 'RSS', 'tribe' ),
			],
			'default_value' => self::FORMAT_NEWS,
		] );

		return $field;
	}

	public function get_taxonomies() {
		return $this->taxonomies;
	}

}
```