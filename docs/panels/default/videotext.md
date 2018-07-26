The Video + Text panel is comprised of a title, text content, an embeded video, and a CTA button.

The layout for this panel is a 2 column layout that places the video on the left or right side (your choice) and has that embeded video stand alone in its column. The opposite side will contain, if provided, the title, text content, and CTA button. On smaller screens, this content will stack vertically placing the left side on top.

## Usage

This is a fairly conventional panel and does not require much explaination. Video on one side and a title, description, and cta button on the other.
Currently, we support YouTube and Vimeo embeds only.

## Fields

### `layout`
* location: `Settings`
* label: `Layout`
* type: `ImageSelect`
* options: `video-left`, `video-right`
* default: `video-left`

### `title`
* location: `Content`
* label: `Title`
* type: `Text`

### `description`
* location: `Content`
* label: `Description`
* type: `TextArea`
* richtext: `true`

### `video`
* location: `Content`
* label: `Video`
* type: `Text`

### `cta`
* location: `Content`
* label: `Call To Action Link`
* type: `Link`

## Components

### [Video](/components_docs/video)
* twig var: `{{ video }}`
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
