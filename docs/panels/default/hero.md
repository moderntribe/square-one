# Hero Panel

The default Hero Panel is comprised of a background image, title, text content, and CTA button.

### Usage

It's often used as the first panel on the page as introductory content.

### File Locations

* **Controller:** `wp-content\plugins\core\src\Templates\Content\Panels\Hero.php`
* **Template:** `wp-content\themes\core\content\panels\hero.twig`

### Fields

#### `layout`
* location: `Settings`
* label: `Layout`
* type: `ImageSelect`
* options: `left`, `right`, `center`
* default: `center`

#### `text_color`
* location: `Settings`
* label: `Text Color`
* type: `Radio`
* options: `light`, `dark`
* default: `dark`

#### `title`
* location: `Content`
* label: `Title`
* type: `Text`
* richtext: `false`

#### `description`
* location: `Content`
* label: `Description`
* type: `TextArea`
* richtext: `true`

#### `image`
* location: `Content`
* label: `Background Image`
* type: `Image`
* size: `medium`

#### `cta`
* location: `Content`
* label: `Call to Action Link`
* type: `Link`

## Components

### [Image](/docs/theme/components/image.md)
* twig var: `{{ image }}`
* twig var type: `string`
* panel field: `image`

### [Content Block](/docs/theme/components/content_block.md)
* twig var: `{{ content_block }}`
* twig var type: `string`
* component imports: `Button`, `Title`, `Text`

### [Button](/docs/theme/components/button.md)
* used by: `Content Block`
* panel field: `cta`

### [Title](/docs/theme/components/title.md)
* used by: `Content Block`
* panel field: `title`

### [Text](/docs/theme/components/text.md)
* used by: `Content Block`
* panel field: `description`