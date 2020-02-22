#  Theme Images

Images for the themes live in a folder named "img" at `/wp-content/themes/core/img/theme`. 

## Template Tags

### the_tribe_image( $image_id, $args )

This template function has been reduced to a wrapper for the Twig Image Component.  View the full set of options and code examples in the the [component's documentation](/docs/frontend/components/image.md).

#### Example Usage:

Using: 
```php
$args = [
    'use_lazyload' => false,
];
the_tribe_image( get_post_thumbnail_id(), $args );
```

Yields:
```html
<figure class="c-image">
    <img class="c-image__image" src="SRC TO LARGE IMAGE" alt="IMG ALT"  />
</figure>
```

Additional usage examples are available in the Image Component's documentation.

## Table of Contents

* Theme
  * [Overview](/docs/frontend/README.md)
  * [Markup and Style](/docs/frontend/markup-and-style.md)
  * [Accessibility](/docs/frontend/accessibility.md)
  * [Image Handling](/docs/frontend/images.md)
  * [Fonts](/docs/frontend/fonts.md)
  * [Icons](/docs/frontend/icons.md)
  * CSS
    * [Overview](/docs/frontend/css/README.md)
    * [PostCSS](/docs/frontend/css/postcss.md)
    * [Grids](/docs/frontend/css/grids.md)
    * [Plugins](/docs/frontend/css/plugins.md)
    * [Forms](/docs/frontend/css/forms.md)
  * Javascript
    * [Overview](/docs/frontend/js/README.md)
    * [Selectors](/docs/frontend/js/selectors.md)
    * [Events](/docs/frontend/js/events.md)
    * [Jquery](/docs/frontend/js/jquery.md)
