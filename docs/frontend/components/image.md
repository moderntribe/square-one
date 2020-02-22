#  Image Component

The Image Component is the primary means by which we inject image markup into the site. The Image component functions somewhat differently than other components as it combines a lot of internal source code generation along with the general output of the image block. Lazy loading, srcset, background images, appending arbitrary html and more are available. 

## File Locations

* **Template:** `wp-content\themes\core\components\image.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\Image.php`
* **PostCSS:** `wp-content\plugins\core\assets\theme\pcss\components\_image.pcss`

## Options

This is the full set of arguments you can modify, and exhibits the defaults:
```php
Image::IMG_ID             => 0,                    // The Image ID - takes precedence over IMG_URL.
Image::IMG_URL            => '',                   // The Image URL - generate markup for an image via its URL. Only applicable if `IMG_ID` is empty.
Image::AS_BG              => false,                // Generates a background image on a `<div>` instead of a traditional `<img>`.
Image::AUTO_SHIM          => true,                 // If true, shim dir as set will be used, src_size will be used as filename, with png as file type.
Image::AUTO_SIZES_ATTR    => false,                // If lazyloading the lib can auto create sizes attribute.
Image::ECHO               => true,                 // Whether to echo or return the html.
Image::EXPAND             => '200',                // The expand attribute is the threshold used by lazysizes. use negative to reveal once in viewport.
Image::HTML               => '',                   // Append an html string inside the wrapper. Useful for adding a `<figcaption>` or other markup after the image.
Image::IMG_CLASSES        => [ 'c-image__image' ], // Pass classes for image tag. if lazyload is true class `lazyload` is auto added.
Image::IMG_ATTRS          => [],                   // Additional image attributes.
Image::IMG_ALT_TEXT       => '',                   // Pass specific image alternate text. If not included, will default to image title.
Image::LINK_URL           => '',                   // Pass a link to wrap the image.
Image::LINK_CLASSES       => [ 'c-image__link' ],  // Pass link classes.
Image::LINK_TARGET        => '',                   // Pass a link target.
Image::LINK_TITLE         => '',                   // Pass a link title.
Image::LINK_ATTRS         => [],                   // Pass additional link attributes.
Image::PARENT_FIT         => 'width',              // If lazyloading this combines with object fit css and the object fit polyfill.
Image::SHIM               => '',                   // Supply a manually specified shim for lazyloading. Will override auto_shim whether true/false.
Image::SRC                => true,                 // Set to false to disable the src attribute. this is a fallback for non srcset browsers.
Image::SRC_SIZE           => 'large',              // This is the main src registered image size.
Image::SRCSET_SIZES       => [],                   // This is registered sizes array for srcset.
Image::SRCSET_SIZES_ATTR  => '(min-width: 1260px) 1260px, 100vw', // This is the srcset sizes attribute string used if auto is false.
Image::USE_HW_ATTR        => false,                // This will set the width and height attributes on the img to be half the original for retina/hdpi. Only for not lazyloading and when src exists.
Image::USE_LAZYLOAD       => true,                 // Lazyload this game? If `AS_BG` is true, `SRCSET_SIZES` must also be defined.
Image::USE_SRCSET         => true,                 // Srcset this game?
Image::WRAPPER_ATTRS      => [],                   // Additional wrapper attributes.
Image::WRAPPER_CLASSES    => [ 'c-image' ],        // Pass classes for figure wrapper. If as_bg is set true gets auto class of "lazyload".
Image::WRAPPER_TAG        => 'figure',             // Html tag for the wrapper/background image container.
```

## Notes

Doing animations/transitions to loaded images should be done with css if desired. When lazyloading, the js plugin applies the "lazyloading" class to the image during loading, and "lazyloaded" when complete. So you could set the image to `opacity: 0` and drop a css transition on, then apply `opacity: 1` on the lazyloaded class. To adjust when the image is revealed adjust the "expand" attribute. Negative integers bring you into viewport, positive trigger it below viewport bottom. Default is 200 so images lazyload a bit before they come into view. To know more about the lazyload javascript go here [Lazysizes](https://github.com/aFarkas/lazysizes). Check the vendor directory of the theme to see which Lazysizes plugins are active, and check the copy grunt task to understand how to add more Lazysizes plugins. 

## Examples

### Basic img without Lazyload
#### Using:
```php
$options = [
    Image::IMG_ID       => get_post_thumbnail_id(),
    Image::USE_LAZYLOAD => false,
];

echo Image::factory( $options )->render();
```
#### Yields:
```html
<figure class="c-image">
    <img class="c-image__image" alt="ALT TEXT" src="SRC TO LARGE IMAGE">
</figure>
```

### Basic background image without lazyload

#### Using:
```php
$options = [
    Image::IMG_ID       => get_post_thumbnail_id(),
    Image::USE_LAZYLOAD => false,
    Image::AS_BG        => true,
];

echo Image::factory( $options )->render();
```

#### Yields:
```html
<figure class="c-image">
    <div class="c-image__image" role="img" aria-label="ALT TEXT" style="background-image:url('SRC TO LARGE IMAGE');"></div>
</figure>
```

### Image with src_size & srcset_sizes of sizes for responsive use. No lazy loading.

#### Using:
```php
$options = [
    Image::IMG_ID       => get_post_thumbnail_id(),
    Image::USE_LAZYLOAD => false,
    Image::SRC_SIZE     => 'slider-small',
    Image::SRCSET_SIZES => [
        'slider-small',
        'slider',
        'slider-full',
    ],
];
echo Image::factory( $options )->render();
```

#### Yields:
```html
<figure class="c-image">
    <img class="c-image__image" src="SRC TO SMALL" srcset="
        SRC TO SMALL 800w 450h ,
        SRC TO MID SIZE 1400w 788h ,
        SRC TO FULL SIZE 2000w 1126h" 
    sizes="(min-width: 1260px) 1260px, 100vw"  alt="IMG ALT"  />
</figure>
```

### Image with src_size & srcset_sizes of sizes for responsive use. Lazy loading enabled. 

#### Using:
```php
$options = [
    Image::IMG_ID       => get_post_thumbnail_id(),
    Image::SRC_SIZE     => 'slider-small',
    Image::SRCSET_SIZES => [
        'slider-small',
        'slider',
        'slider-full',
    ],
];
echo Image::factory( $options )->render();
```

#### Yields:
```html
<!--  used src_size as filename for shim loaded from theme/img/shims, shim applied to src and srcset, lazy load uses data attrs -->
<figure class="c-image">
    <img 
    class="lazyload c-image__image" 
    src="http://square1.tribe/wp-content/themes/core/img/shims/slider-small.png"  
    srcset="http://square1.tribe/wp-content/themes/core/img/shims/slider-small.png"  
    data-src="SRC TO SMALL"  
    data-srcset="
        SRC TO SMALL 800w 450h ,
        SRC TO MID SIZE 1400w 788h ,
        SRC TO FULL SIZE 2000w 1126h"  
    data-sizes="(min-width: 1260px) 1260px, 100vw"  
    data-expand="200"  
    data-parent-fit="width"  
    alt="IMG ALT"  />
</figure>
```

### Image with custom wrapper tag, attributes, link around image, and lazy loading.

#### Using:
```php
$options = [
    Image::IMG_ID         => get_post_thumbnail_id(),
    Image::WRAPPER_TAG    => 'article',
    Image::WRAPPER_ATTRS  => [ 'data-funky' => 'Stanley Clarke' ],
    Image::IMG_ATTRS      => [ 'data-image' => 'Yes' ],
    Image::LINK_URL       => 'http://www.google.com',
    Image::SRC_SIZE       => 'slider-small',
    Image::SRCSET_SIZES   => [
        'slider-small',
        'slider',
        'slider-full',
    ],
];
echo Image::factory( $options )->render();
```

#### Yields:
```html
<!--  used src_size as filename for shim loaded from theme/img/shims, shim applied to src and srcset, lazy load uses data attrs -->
<article data-funky="Stanley Clarke" class="c-image">
    <a href="http://www.google.com">
        <img 
        class="c-image__image lazyload" 
        src="http://square1.tribe/wp-content/themes/core/img/shims/slider-small.png"  
        srcset="http://square1.tribe/wp-content/themes/core/img/shims/slider-small.png"  
        data-src="SRC TO SMALL"  
        data-srcset="
            SRC TO SMALL 800w 450h ,
            SRC TO MID SIZE 1400w 788h ,
            SRC TO FULL SIZE 2000w 1126h"  
        data-sizes="(min-width: 1260px) 1260px, 100vw"  
        data-expand="200"  
        data-parent-fit="width" 
        data-image="Yes"
        alt="IMG ALT"  />
    </a>
</article>
```

### Background image with custom wrapper tag, link around image, and lazy loading.

#### Using:
```php
$options = [
    Image::IMG_ID          => get_post_thumbnail_id(),
    Image::AS_BG           => true,
    Image::WRAPPER_TAG     => 'div',
    Image::WRAPPER_CLASSES => [ 'c-image', 'c-image--rect' ],
    Image::IMG_CLASSES     => [ 'c-image__bg' ],
    Image::LINK_URL        => 'http://www.google.com',
    Image::SRC_SIZE     => 'slider-small',
    Image::SRCSET_SIZES => [
        'slider-small',
        'slider',
        'slider-full',
    ],
];

echo Image::factory( $options )->render();
```

#### Yields:
```html
<!--  used src_size as filename for shim loaded from theme/img/shims, shim applied to style:background-image and bgset, lazy load uses data attrs -->
<div class="c-image c-image--rect">
    <a href="http://www.google.com" class="c-image__link">
        <div
            class="c-image__bg lazyload"
            role="img"
            aria-label="G"
            data-expand="200"
            data-bgset="
                SRC TO SMALL 800w 450h ,
                SRC TO MID SIZE 1400w 788h ,
                SRC TO FULL SIZE 2000w 1126h"
            style="background-image: url(http://square1.tribe/wp-content/themes/core/img/shims/slider-small.png);">
        </div>
    </a>
</div>
```

## Table of Contents

* [Overview](/docs/frontend/components/README.md)
* [Accordion](/docs/frontend/components/accordion.md)
* [Button](/docs/frontend/components/button.md)
* [Card](/docs/frontend/components/card.md)
* [Content Block](/docs/frontend/components/content_block.md)
* [Image](/docs/frontend/components/image.md)
* [Quote](/docs/frontend/components/quote.md)
* [Slider](/docs/frontend/components/slider.md)
* [Tabs](/docs/frontend/components/tabs.md)
* [Template](/docs/frontend/components/template.md)
* [Text](/docs/frontend/components/text.md)
