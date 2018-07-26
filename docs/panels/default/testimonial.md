The default Testimonials panel is comprised of a title, background image, and a content slider.

## Usage

Let the users of your site know about all the wonderful things people say about your business, product, service, and/or its employees. This panel uses an optional large background image to set a single backdrop for a slider that displays quotes and allows you to cite the author.

Navigate through the slides by swiping(device/browser dependant) or using the navigation bullets.

## Fields

### `text_color`
* location: `Settings`
* label: `Text Color`
* type: `Radio`
* options: `t-content--light`, `t-content--dark`
* default: `t-content--dark`

### `title`
* location: `Content`
* label: `Title`
* type: `Text`

### `image`
* location: `Content`
* label: `Background Image`
* type: `Image`
* size: `medium`

### `quotes`
* location: `Content`
* label: `Testimonials`
* type: `Repeater`
* min: `1`
* max: `4`

### `quote`
* location: `Content`
* label: `Quote`
* type: `TextArea`
* richtext: `false`

### `cite`
* location: `Content`
* label: `Cite`
* type: `Text`

## Components

### [Image](/components_docs/image)
* twig var: `{{ image }}`
* twig var type: `string`
* panel field: `image`

### [Slider](/components_docs/slider)
* twig var: `{{ slider }}`
* twig var type: `string`
* component children: `Quote`

### [Quote](/components_docs/quote)
* child of: `Slider`
* panel field: `quotes`
