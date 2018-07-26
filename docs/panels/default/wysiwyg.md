The default WYSIWYG Panel is comprised of a title, text content, and an array of WYSIWYG editor blocks.

Sometimes you need to lay out content your own way. This panel allows you to use the WordPress WYSIWYG editor to lay out text and images in a single column, two or even three columns.

## Usage

Displaying text and/or images, embedding YouTube videos, or social media feeds.

## Fields

### `title`
* label: `Title`
* type: `Text`

### `description`
* label: `Description`
* type: `TextArea`
* richtext: `true`

### `columns`
* label: `Columns`
* type: `Repeater`
* min: `1`
* max: `3`

### `column_content`
* label: `Column Content`
* type: `TextArea`
* richtext: `true`
