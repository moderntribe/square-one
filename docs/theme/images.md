#  Theme Images

Images for the theme usually live in a folder named "img" at root of theme. 

## Template Tags

### the_tribe_image( $image_id, $args )

This is the main template tag we want to use for outputting an image. It offers a varity of features with minimal config on your part.

Lazyloading, srcset, background images, appending arbitrary html and more are available. This is the full set of arguments you can modify, and exhibits the defaults:
```php
	'src'               => true,              // set to false to disable the src attribute. this is a fallback for non srcset browsers
	'src_size'          => 'large',           // this is the main src registered image size
	'srcset_sizes'      => [],                // this is registered sizes array for srcset.
	'shim'              => '',                // supply a manually specified shim for lazyloading. Will override auto_shim whether true/false.
	'auto_shim'         => true,              // if true, shim dir as set will be used, src_size will be used as filename, with png as filetype
	'as_bg'             => false,             // us this as background on wrapper?
	'use_lazyload'      => true,              // lazyload this game?
	'use_srcset'        => true,              // srcset this game?
	'auto_sizes_attr'   => false,             // if lazyloading the lib can auto create sizes attribute.
	'srcset_sizes_attr' => '(min-width: 1260px) 1260px, 100vw', // this is the srcset sizes attribute string used if auto is false.
	'expand'            => '200',             // the expand attribute is the threshold used by lazysizes. use negative to reveal once in viewport.
	'parent_fit'        => 'width',           // if lazyloading this combines with object fit css and the object fit polyfill
	'img_class'         => '',                // pass classes for image tag. if lazyload is true class "lazyload" is auto added
	'wrapper_class'     => 'tribe-image',     // pass classes for figure wrapper. If as_bg is set true gets auto class of "lazyload"
	'link'              => '',                // pass a link to wrap the image
	'link_target'       => '_self',           // pass a link target
	'echo'              => true,              // whether to echo or return the html
	'html'              => '',                // append an html string in the wrapper
```

Doing animations/transitions to loaded images should be done with css if desired. When lazyloading, the js plugin applies the "lazyloading" class to the image during loading, and "lazyloaded" when complete. So you could set the image to `opacity: 0` and drop a css transition on, then apply `opacity: 1` on the lazyloaded class. To adjust when the image is revealed adjust the "expand" attribute. Negative integers bring you into viewport, positive trigger it below viewport bottom. Default is 200 so images lazyload a bit before they come into view. To know more about the lazyload javascript go here [Lazysizes](https://github.com/aFarkas/lazysizes). Check the vendor directory of the theme to see which Lazysizes plugins are active, and check the copy grunt task to understand how to add more Lazysizes plugins. 

Some example usage and generated output to help you along:

Using: 
```php
	$args = [
		'use_lazyload' => false,
	];
	the_tribe_image( get_post_thumbnail_id(), $args );
```
Yields:
```html
	<figure class="tribe-image">
		<img class="" src="SRC TO LARGE IMAGE" alt="IMG ALT"  />
	</figure>
```
Using: 
```php
	$args = [
	    'use_lazyload' => false,
	    'as_bg' => true,
	];
	the_tribe_image( get_post_thumbnail_id(), $args );
```
Yields:
```html
	<div style="background-image:url('SRC TO LARGE IMAGE');"  class="tribe-image"></div>
```
Using: 
```php
	$args = [
        'use_lazyload' => false,
        'src_size' => 'slider-small',
        'srcset_sizes' => [
            'slider-small',
            'slider',
            'slider-full',
        ],
    ];
    the_tribe_image( get_post_thumbnail_id(), $args );
```
Yields:
```html
	<figure class="tribe-image">
		<img src="SRC TO SMALL" srcset="
			SRC TO SMALL 800w 450h ,
            SRC TO MID SIZE 1400w 788h ,
            SRC TO FULL SIZE 2000w 1126h" 
        sizes="(min-width: 1260px) 1260px, 100vw"  alt="IMG ALT"  />
    </figure>
```
Using: 
```php
	$args = [
        'src_size' => 'slider-small',
        'srcset_sizes' => [
            'slider-small',
            'slider',
            'slider-full',
        ],
    ];
    the_tribe_image( get_post_thumbnail_id(), $args );
```
Yields (used src_size as filename for shim loaded from theme/img/shims, shim applied to src and srcset, lazyload uses data atts):
```html
	<figure class="tribe-image">
		<img 
		class="lazyload" 
		src="http://square.dev/wp-content/themes/core/img/shims/slider-small.png"  
		srcset="http://square.dev/wp-content/themes/core/img/shims/slider-small.png"  
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