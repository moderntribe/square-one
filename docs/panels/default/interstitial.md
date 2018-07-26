The default Interstitial Panel is comprised of a background image, title, text content, and CTA button.

Definition: **Interstitial** _adjective_ | of, forming, or occupying interstices: _the interstitial space_.

## Usage

Similar to the [Hero Panel](/components_docs/hero), the Interstitial panel looks very much the same out of the box but is intended to have our world class design and development team customize this. The end result is usually a large hero style block that breaks up your page content and can be intersperced between other panels.

## Fields

### `layout`
* location: `Settings`
* label: `Layout`
* type: `ImageSelect`
* options: `content-right`, `content-left`, `content-center`
* default: `content-center`

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
* richtext: `false`

### `description`
* location: `Content`
* label: `Description`
* type: `TextArea`
* richtext: `true`

### `image`
* location: `Content`
* label: `Background Image`
* type: `Image`
* size: `medium`

### `cta`
* location: `Content`
* label: `Call to Action Link`
* type: `Link`

## Components

### [Image](/components_docs/image)
* twig var: `{{ image }}`
* twig var type: `string`
* panel field: `video`

### [Content Block](/components_docs/content_block)
* twig var: `{{ content_block }}`
* twig var type: `string`
* component imports: `Button`, `Title`, `Text`

### [Button](/components_docs/button)
* used by: `Content Block`
* panel field: `cta`

### [Title](/components_docs/title)
* used by: `Content Block`
* panel field: `title`

### [Text](/components_docs/text)
* used by: `Content Block`
* panel field: `description`