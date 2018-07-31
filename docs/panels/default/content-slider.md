# Content Slider Panel

The Content Slider panel is comprised of a title, and an array of slides.

The slides are each comprised of an image, title, text content, and CTA button.

## Usage

Whether you want a slider of just images, just content or images with content on top of them, this panel meets all those needs. Sometimes you just want a slider somewhere within your content to support the page or post.

### File Locations

* **Controller:** `wp-content\plugins\core\src\Templates\Content\Panels\ContentSlider.php`
* **Template:** `wp-content\themes\core\content\panels\content-slider.twig`

## Fields

### `title`
* label: `Title`
* type: `Text`

### `slides`
* label: `Slides`
* type: `Repeater`
* min: `2`
* max: `5`

### `slide_img`
* label: `Image`
* type: `Image`
* size: `medium`

### `slide_title`
* label: `Title`
* type: `Text`

### `slide_content`
* label: `Image`
* type: `TextArea`
* richtext: `true`

### `slide_cta`
* label: `Call To Action Link`
* type: `Link`

## Components

### [Slider](/docs/theme/components/slider.md)
* twig var: `{{ slider }}`
* twig var type: `string`
* component imports: `Image`, `Content_Block`
* panel field: `slides`

### [Image](/docs/theme/components/image.md)
* used by: `Slider`
* panel field: `slide_img`

### [Content Block](/docs/theme/components/content_block.md)
* used by: `Slider`
* component imports: `Button`, `Title`, `Text`

### [Button](/docs/theme/components/button.md)
* used by: `Content Block`
* panel field: `slide_cta`

### [Title](/docs/theme/components/title.md)
* used by: `Content Block`
* panel field: `slide_title`

### [Text](/docs/theme/components/text.md)
* used by: `Content Block`
* panel field: `slide_content`
