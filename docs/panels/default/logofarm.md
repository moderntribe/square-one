The Logo Farm panel is comprised of a title, text content, and an array of small(ish) images.

## Usage

Want to plant, grow and harvest enough logos to feed a small village? This is your panel. Sometimes you need to display a list of associated brands, companies you've colaborated with, or clients who you've done work for. This panel allows you to achive that goal by placing logo images in a single line horizontal grid.

## Fields

### `title`
* label: `Title`
* type: `Text`

### `description`
* label: `Description`
* type: `TextArea`
* richtext: `true`

### `logos`
* label: `Logos`
* type: `Repeater`
* min: `2`
* max: `6`

### `logo_image`
* label: `Image`
* type: `Image`
* size: `medium`

### `logo_cta`
* label: `Logo Link`
* type: `Link`

## Components

### [Image](/components_docs/image)
* twig var: `{{ logos }}`
* twig var type: `array`
* panel fields: `logo_image`, `logo_cta`
