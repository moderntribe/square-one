The Content Slider panel is comprised of a title, and an array of slides.

The slides are each comprised of an image, title, text content, and CTA button.

## Usage

Whether you want a slider of just images, just content or images with content on top of them, this panel meets all those needs. Sometimes you just want a slider somewhere within your content to support the page or post.

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

### [Slider](/components_docs/slider)
* twig var: `{{ slider }}`
* twig var type: `string`
* component imports: `Image`, `Content_Block`
* panel field: `slides`

### [Image](/components_docs/image)
* used by: `Slider`
* panel field: `slide_img`

### [Content Block](/components_docs/content_block)
* used by: `Slider`
* component imports: `Button`, `Title`, `Text`

### [Button](/components_docs/button)
* used by: `Content Block`
* panel field: `slide_cta`

### [Title](/components_docs/title)
* used by: `Content Block`
* panel field: `slide_title`

### [Text](/components_docs/text)
* used by: `Content Block`
* panel field: `slide_content`
