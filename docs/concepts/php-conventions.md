# PHP Conventions


## Factory Method

Many classes—including all post types classes (which extend `Post_Object`) and taxonomy classes
(which extend `Term_Object` )—contain a static `factory()` method.  This is used to retrieve
a specific instance of a class.  Typically, an id is passed to retrieve it.

```php
$story = Feed_Story::factory( $post_id );
echo $story->get_source_name();
```
