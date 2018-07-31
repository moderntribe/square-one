# Gallery Panel

The Gallery panel is comprised of a panel title, text content, and a multi-select image gallery. The gallery field utilizes the baked in WordPress Media Library gallery feature for adding, removing and reordering the images in your gallery.

### Usage

The gallery panel has 4 possible layouts that can be used.
* You can use cropped or un-cropped images.
    * Un-cropped allows for all images to maintain their original aspect ratio and displays the entire image.
    * Cropped uses a predefined cropped size for all images and tries to make them consistent with other images in the gallery.
* You can also choose to have a carousel thumbnail navigation with your gallery.
    * The thumbnail nav synchronizes with the main gallery slider and controls the current slide being displayed.
    * There is an option to turn this on or off.

The default layout is un-cropped images with a carousel.

### File Locations

* **Controller:** `wp-content\plugins\core\src\Templates\Content\Panels\Gallery.php`
* **Template:** `wp-content\themes\core\content\panels\galery.twig`

### Fields

#### `image_treatment`
* location: `Settings`
* label: `Image Treatment`
* type: `ImageSelect`
* options: `letterbox`, `crop`
* default: `letterbox`

#### `carousel`
* location: `Settings`
* label: `Show Carousel`
* type: `ImageSelect`
* options: `carousel_show`, `carousel_hide`
* default: `carousel_show`

#### `title`
* location: `Content`
* label: `Title`
* type: `Text`

#### `description`
* location: `Content`
* label: `Description`
* type: `TextArea`
* richtext: `true`

#### `gallery`
* location: `Content`
* label: `Image Gallery`
* type: `ImageGallery`

## Components

### [Slider](/docs/theme/components/slider.md)
* twig var: `{{ slider }}`
* twig var type: `string`
* component imports: `Image`

### [Image](/docs/theme/components/image.md)
* used by: `Slider`
* panel field: `gallery`
