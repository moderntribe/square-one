# Card Grid Panel

The Card Grid panel is comprised of a panel title, text content, and a repeater field section to add, remove, and reorder cards.

Cards within the repeater are comprised of a title, description, image, and a CTA button.

### Usage

The Card Grid panel gives the appearance of typical card style loop you normally see in an E-commerce store, Pinterest (minus the mosaic layout), and other waffle style grid layouts. This panel allows you to create an easy loop of grid style CTAs for your site.

### File Locations

* **Controller:** `wp-content\plugins\core\src\Templates\Content\Panels\CardGrid.php`
* **Template:** `wp-content\themes\core\content\panels\cardgrid.twig`

### Fields

#### `title`
* label: `Title`
* type: `Text`

#### `description`
* label: `Description`
* type: `TextArea`
* richtext: `true`

#### `cards`
* label: `Cards`
* type: `Repeater`
* min: `2`
* max: `4`

#### `card_title`
* label: `Card Title`
* type: `Text`

#### `card_description`
* label: `Card Description`
* type: `TextArea`
* richtext: `true`

#### `card_image`
* label: `Image`
* type: `Image`
* size: `medium`

#### `car_cta`
* label: `Card Call To Action Link`
* type: `Link`

## Components

### [Card](/docs/theme/components/card.md)
* twig var: `{{ cards }}`
* twig var type: `array`
* component imports: `Image`, `Title`, `Text`, `Button`
* panel field: `cards`

### [Image](/docs/theme/components/image.md)
* used by: `Card`
* panel field: `card_img`

### [Title](/docs/theme/components/title.md)
* used by: `Card`
* panel field: `card_title`

### [Text](/docs/theme/components/text.md)
* used by: `Card`
* panel field: `card_description`

### [Button](/docs/theme/components/button.md)
* used by: `Card`
* panel field: `card_cta`
