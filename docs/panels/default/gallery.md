The Gallery panel is comprised of a panel title, text content, and a multi-select image gallery. The gallery field utilizes the baked in WordPress Media Library gallery feature for adding, removing and reordering the images in your gallery.

### Usage

The gallery panel has 4 possible layouts that can be used.
* You can use cropped or uncropped images.
    * Uncropped allows for all images to maintain their original aspect ratio and displays the entire image.
    * Cropped uses a predefined cropped size for all images and tries to make them consistnet with other images in the gallery.
* You can also choose to have a carousel thumbnail navigation with your gallery.
    * The thumbnail nav synchronizes with the main gallery slider and controls the current slide being displayed.
    * There is an option to turn this on or off.

The default layout is uncropped images with a carousel.


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
