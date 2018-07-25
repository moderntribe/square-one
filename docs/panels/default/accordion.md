The default Accordion Panel is comprised of a panel title, text content, and a repeater field section for accordion rows. Each row is comprised of an title and text content. The title field, when rendered on the front-end, has a click event attached to it and is used to animate the show/hide effect for each Accordion Content section.

### Usage

A typical use case for this panel is FAQs.

### Fields

#### `layout`
* location: `Settings`
* label: `Layour`
* type: `ImageSelect`
* options: `right`, `left`, `center`
* default: `right`

#### `title`
* location: `Content`
* label: `Title`
* type: `Text`

#### `description`
* location: `Content`
* label: `Description`
* type: `TextArea`
* richtext: `true`

#### `accordions`
* location: `Content`
* label: `Accordion Rows`
* type: `Repeater`
* min: `1`
* max: `10`

#### `title`
* location: `Content`
* label: `Accordion Title`
* type: `Text`

#### `row_content`
* location: `Content`
* label: `Accordion Content`
* type: `TextArea`
* richtext: `true`

