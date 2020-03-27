## Required Pages

We often require certain pages to exist on a site. They should be automatically
created and if a user unwittingly deletes them, they should be re-created.

Here's how to do this with a hypothetical "Contact Us" page:

1. Create a class that extends `Tribe\Project\Content\Required_Page` and implement the required methods.

```php
class Contact_Page extends Required_Page {
	const NAME = 'contact_page';

	protected function get_title() {
		return _x( 'Contact Us', 'contact page title', 'tribe' );
	}

	protected function get_slug() {
		return _x( 'contact', 'contact page slug', 'tribe' );
	}
}
```

2. Register that class in `Content_Definer`.

```php
self::REQUIRED_PAGES => [
    DI\get( Contact_Page::class ),
]
```

The page will now be automatically created. The ID of the page can be found with:

```php
get_field( Contact_Page::NAME, 'option' )
```

If you want the user to expose a UI for the option, you can do so manually in any Object Meta group,
or you can pass a group key into the constructor for the class to have the field automatically exposed.

```php
Contact_Page::class => function ( ContainerInterface $container ) {
    return new Contact_Page( $container->get( \Tribe\Project\Object_Meta\Example::class )->get_group_config()['key'] );
}
```
