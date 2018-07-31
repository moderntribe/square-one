#  Panel

Introductory content goes here.

## Usage

Typical use case for this panel.

## File Locations

* **Controller:** `wp-content\plugins\core\src\Templates\Content\Panels\.php`
* **Template:** `wp-content\themes\core\content\panels\.twig`

## Fields

### `field_one`
* location: `Settings`
* label: `Field One`
* type: `ImageSelect`
* options: `left`, `right`, `center`
* default: `center`

### `field_two`
* location: `Content`
* label: `Field Two`
* type: `Radio`
* options: `light`, `dark`
* default: `dark`

## Components

### [Component Name](/docs/theme/components/.md)
* twig var: `{{ var_name }}`
* twig var type: `string`
* component imports: `Component1`, `Component2`
* panel field: `field_name`

### [Image](/docs/theme/components/image.md)
* used by: `Parent Component`
* panel field: `field_name`