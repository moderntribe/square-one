# === Object Meta ===

Object Meta (or custom meta fields assigned to any Post, Taxonomy, User, or Settings object) is powered by the Advanced Custom Fields plugin. Within SquareOne, we've
developed an abstraction methodology for registering Object Meta that is consistent across all of the available Object Types. This means that the process for registering
any piece of meta will be the same, whether you're adding it to a Post, a User, a Taxonomy, or Settings Page (or all four!)

## Registering Object Meta

The first step to setting up Object Meta is to create the Class that will define the fields for this particular Meta Group. Keep in mind that each Object Meta Class 
you register will function as an ACF Meta Group by default; it will be contained in a single metabox and formatted with standard ACF parameters such as position, 
priority, and layout. For advanced use-cases, this can be overridden by overwriting the base class's `register_group()` method to register more than one Meta Group/metabox.

Object Meta classes should all go in the `core/src/Object_Meta` directory. Since Object Meta can be (and often is) shared amongst multiple Post Types or even Object Types,
it's always best practice to try to choose a name that is general enough to apply to each context it will be used, but specific enough to make it easy to tell what
types of fields the Class holds. 

**Bad Name**

`Post_Department_Title_and_Meta.php`

**Better Name**

`Department_Information.php`

An Object Meta class should extend `Tribe\Libs\ACF\ACF_Meta_Group`, and has 3 distinct pieces of information it must provide.

### Constants

First, it's important to set up any strings you're going to use within the meta fields as constants. Using constants allows us to reference these strings throughout 
the rest of the codebase in a way that, if updated, will flow throughout the project without having to do a find/replace on multiple files.

The only *required* constant is `NAME`. This constant is used in the registration process and should be unique to this Object Meta.

Once you have a `NAME`, you'll want to add constants for any field name or other strings you need. Working from the `Example.php` class, we see the following:

```
const NAME = 'example_meta';

const ONE = 'example_object_meta_one';
const TWO = 'example_object_meta_two';
```

### get_keys()

Every Object Meta Class needs to define a public `get_keys()` method. This method is used during registration and other areas to know exactly which keys this Object 
Meta makes available to the rest of the codebase. Any key you want to use should be listed here as an array. If you don't include it here, you'll get `null` whenever 
you try to access it from within a Controller. From `Example.php` again:

```
public function get_keys() {
	return [
		static::ONE,
		static::TWO,
	];
}
```

Note that we simply return an array of the constants we defined for our field keys. This will allow all of our keys to be exposed to other pieces of the codebase.
Depending on your needs and what you wish to expose, you may wish to include only a subset of your meta keys in this array.

### get_group_config()

The final step is adding a public `get_group_config()` method. This method handles actually instantiating the group, adding any parameters, and adding individual fields
to the group.

The first step is instantiating the group and adding parameters:

```
$group = new ACF\Group( self::NAME, $this->object_types );
$group->set( 'title', __( 'Example Object Meta', 'tribe' ) );
```

Here we simply call a new instance of `ACF\Group()` and pass in our `NAME` constant as the first parameter and the property `object_types` as the second. This property
is set up during registration; you don't need to explicitly define it in your class.

This is also where you would set any Group parameters available in ACF, such as position or priority.

Next, we begin adding fields to the Group. Typically, we encourage having separate methods for each field which return the field. This makes the class more organized 
and easier to maintain down the road. From `Example.php`:

```
$group->add_field( $this->get_field_one() );
$group->add_field( $this->get_field_two() );
```

We'll touch on the method to return a field a bit further on in the docs. For now, simply note that for each field, you call `$group->add_field()`;

Finally, this method needs to return the `get_attributes()` method of the `$group`. Note that we aren't returning the `$group` variable itself, but rather the `get_attributes()`
method specifically:

```
return $group->get_attributes();
```

So, putting it all together:


```
public function get_group_config() {
$group = new ACF\Group( self::NAME, $this->object_types );
$group->set( 'title', __( 'Example Object Meta', 'tribe' ) );

$group->add_field( $this->get_field_one() );
$group->add_field( $this->get_field_two() );

return $group->get_attributes();
}
```

### Returning fields

If you use a distinct method for each field type, you'll need to follow two steps. First, you need to instantiate a new field. This field can be any ACF Field type. 
One thing to note is that you'll pass in a concatenated string for the parameter in the Field construct. This string should be your `NAME` constant for the class, followed
by `_`, followed by the constant for the unique field name. For instance from `Example.php`:

```
$field = new ACF\Field( self::NAME . '_' . self::ONE );
```

Once you instantiate it, you'll call the `set_attributes()` method on the field and pass in an array of arguments for the field. Note that this supports any arguments 
that the field in ACF would support. One useful step can often be to use ACF's built-in export tool to get the PHP code for a field you built with the UI. You can then simply
copy the arguments array from there and paste it into your method, cleaning out any extra arguments you aren't using. 

Finally, you need to return your `$field`;

Altogether, it looks like so:

```
private function get_field_one() {
	$field = new ACF\Field( self::NAME . '_' . self::ONE );
	$field->set_attributes( [
		'label' => __( 'Example Object Meta #1', 'tribe' ),
		'name'  => self::ONE,
		'type'  => 'text',
	] );

	return $field;
}
```

## Registering the Object Meta in the Service Provider

Now that you've created your Object_Meta class, it's time to register it with the Service Provider. First you'll want to find `core/src/Service_Providers/Object_Meta_Provider.php`.

This is the Service Provider which handles identifying each Object Meta group and routing it to the correct Object Types. Registering a new Object Meta class here 
has a few steps.

### Add Constants

The first step is to add a constant to the Provider to identify your Object Meta within the Pimple Container. For `Example.php`, we use:

```
const EXAMPLE = 'object_meta.example';
```

### Add Key to $keys Property

The next step is to add your constant to the `$keys` property for the Provider (much as we did in the Object Meta class). This array is used to identify which Object 
Meta classes should be registered by ACF on init. For `Example.php`:

```
private $keys = [
	self::EXAMPLE,
];
```

### Add a Registration Method

Each Object Meta class should have its own distinct method within the Provider. This allows better maintainability and makes the file easier to parse in the future. 
Your registration method should accept the Pimple Container as the argument, and add an instance of your Object Meta class to the container. For our `Example` class,
it looks like so:

```
$container[ self::EXAMPLE ] = function ( Container $container ) {
	return new Example( [] );
};
```

Note that we use the constant we set up for our Meta as the `$container` array key, and that it simply returns a new instance of our `Example` class.

As it is, this doesn't actually associate the Meta with any particular Object types. To do this, we need to pass the array of Object types we want to reference via
the argument for our Meta class (in this case, `Example`). There are 4 Object types which can be referenced:

* `post_types`
* `taxonomies`
* `users`
* `settings_pages`

You can add any of these to the array, and then specify which _specific_ items within those object types you want to use. Each Object type requires a slightly different
array of items:


|Object Type|Parameters|
|---|---|
|post_types|Post Types are identified by their class's `NAME` constant. For instance: `[ Post_Types\Page::NAME, Post_Types\Events::NAME ]`|
|taxonomies|Taxonomies are identified by their class's `NAME` constant. For instance `[ Taxonomies\Category\Category::NAME ]`|
|users| Users does not take an array of items, but simply accepts either `true` or `false`|
|settings_pages|Settings pages are identified by the `slug` for any given Settings Page. This can be accessed like so: `[ Settings\General::instance()->get_slug() ]`|


Here's an example for `Example` that adds values for each object type:

```
private function example( Container $container ) {
	$container[ self::EXAMPLE ] = function ( Container $container ) {
		return new Example( [
			'post_types'     => [ Post_Types\Page\Page::NAME, Post_Types\Post\Post::NAME ],
			'taxonomies'     => [ Taxonomies\Category\Category::NAME ],
			'settings_pages' => [ Settings\General::instance()->get_slug() ],
			'users'          => true,
		] );
	};
}
```

### Call the Registration Method

Once your registration method is complete, you need to call it from within the Provider's `register()` method. Be sure to pass the Pimple Container as the parameter:

```
$this->example( $container );
```

Calls to the registration method should go at the top of the `register()` method in the Provider.

## Accessing Object Meta on the Front End

The general pattern for accessing registered Object Meta is the following (shown here for a Post object). Imagine that we’ve registered an object meta with a class of `Example_Meta` which has a field in it referenced by the constant `HEADER_IMAGE`:

```
$post_object = Post::factory( $post_id );
$example_meta = $post_object->get_meta( Example_Meta::HEADER_IMAGE );

echo $example_meta.....
```


This code would echo out the value saved to the `HEADER_IMAGE` meta field for that post. 

One update we made to how meta works is that we’ve added a `Term_Object` that functions much in the same way as a `Post_Object`, but applies to taxonomy terms. This allows us to grab meta from a Term in the same way that we would a Post. For example:

```
$term_object = Category::factory( $term_id );
$example_meta = $term_object->get_meta( Example_Meta::HEADER_IMAGE );

echo $example_meta.....
```

This would then echo out the value saved tot he `HEADER_IMAGE` field, but for that specific Taxonomy Term instead. 

Post types, Taxonomies, and Users have the `_Object` classes avaialable for retrieving the correct meta values using the previously-described patterns. Note that Post_Objects
accept a `$post_id`, Term_Objects accept a `$term_id`, and User_Objects accept a `$user_id`.
 
At this time, Settings pages do not have those objects; the recommendation is to get meta values from those object types using ACF’s standard `get_field()` functionality. 

For Settings:

```
$example_meta = get_field( Example_Meta::HEADER_IMAGE, 'option' );

echo $example_meta.....
```