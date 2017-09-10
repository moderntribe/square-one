#  Accordion Component

The accordion component is a simple component for title/content row ui's with clickable titles that expand the associated content.  

This component has these features out of box:  

* Full accessibility baked in.
* Lightweight CSS based height animations
* Full support for livetext in panel instances, including in the repeater rows and titles
* Intelligent editing support during live panel preview, rows expand according to the current one being editing in the panel ui.
* One item open at a time, with scrolling to keep items on screen. Easily switchable in the js.

### File Locations

* **Template:** `wp-content\themes\core\components\accordion.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\Accordion.php`
* **Javascript:** `wp-content\plugins\core\assets\theme\js\src\components\accordion.js`
* **PostCSS:** `wp-content\plugins\core\assets\theme\pcss\components\_accordion.pcss`

### Options

#### `container_classes` 
* **Default:** _c-accordion_ 
* **Type:** _string_ 
* **Description:** the wrapper classes
#### `container_attrs` 
* **Default:** _data-js="c-accordion"_ 
* **Type:** _string_ 
* **Description:** any additional data or other attributes
#### `row_classes` 
* **Default:** _c-accordion__row_
* **Type:** _string_ 
* **Description:** the row classes
#### `row_content_classes` 
* **Default:** _c-accordion__content_ 
* **Type:** _string_ 
* **Description:** the row content wrapper classes
#### `row_content_inner_classes` 
* **Default:** _c-accordion__content-inner_ 
* **Type:** _string_ 
* **Description:** the row content inner classes
#### `row_content_name` 
* **Default:** _accordion_content_ 
* **Type:** _string_ 
* **Description:** the content name attribute, if used in panel supply row content field name
#### `row_header_classes` 
* **Default:** _c-accordion__header_ 
* **Type:** _string_ 
* **Description:** the row header classes
#### `row_header_inner_classes` 
* **Default:** _c-accordion__header-inner_ 
* **Type:** _string_ 
* **Description:** The row header inner classes. wraps the row title text
#### `row_header_name` 
* **Default:** _title_ 
* **Type:** _string_ 
* **Description:** the header name attribute, if used in panel supply row header text field name
#### `rows` 
* **Default:** _[]_ 
* **Type:** _array_ 
* **Description:** an array of accordion rows.
#### `rows[].content` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** The row content. HTML or what have you.
#### `rows[].content_id` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** Unique id for the content.
#### `rows[].header_id`
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** Unique id for the header.
#### `rows[].header_text` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** The header text.

### Example Usage

An accordion panel twig file, being passed the 'accordion' var that was assembled by the panel controller using the accordion component.

```twig
{# Panel: Accordion #}

{% extends "content/panels/panel.twig" %}

{% block content %}

	<div class="s-content">
		<div class="l-container">
			<div class="g-row">
				<div class="{{ grid_classes }}"
					 data-depth="0"
					 data-name="accordions"
					 data-livetext
				>
					<div
						class="g-col"
						data-depth="0"
						data-name="content"
						data-autop="true"
						data-livetext
					>
						{{ content }}
					</div>
					<div class="g-col">
						{{ accordion }}
					</div>
				</div>
			</div>
		</div>
	</div>

{% endblock %}
```

And the panel controller using the accordion component

```php
namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Accordion as AccordionPanel;
use Tribe\Project\Templates\Components\Accordion as AccordionComponent;

class Accordion extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'content'   => $this->panel_vars[AccordionPanel::FIELD_CONTENT],
			'accordion' => $this->get_accordion(),
		];

		return $data;
	}

	protected function get_accordion(): string {
		$options = [
			AccordionComponent::ROWS => $this->get_rows(),
		];

		$accordion = AccordionComponent::factory( $options );

		return $accordion->render();
	}

	protected function get_rows(): array {
		$rows = $this->panel_vars[ AccordionPanel::FIELD_ACCORDIONS ];

		if ( empty( $rows ) ) {
			return [];
		}

		return array_map( function ( $row ) {
			$header_id  = uniqid( 'accordion-header-' );
			$content_id = uniqid( 'accordion-content-' );

			return [
				'header_id'   => $header_id,
				'content_id'  => $content_id,
				'header_text' => $row[ AccordionPanel::FIELD_ACCORDION_TITLE ],
				'content'     => $row[ AccordionPanel::FIELD_ACCORDION_CONTENT ],
			];
		}, $rows );
	}
}

```

### Table of Contents

* [Overview](/docs/theme/components/README.md)
* [Accordion](/docs/theme/components/accordion.md)
* [Button](/docs/theme/components/button.md)
* [Card](/docs/theme/components/card.md)
* [Content Block](/docs/theme/components/content_block.md)
* [Follow](/docs/theme/components/follow.md)
* [Image](/docs/theme/components/image.md)
* [Quote](/docs/theme/components/quote.md)
* [Share](/docs/theme/components/share.md)
* [Slider](/docs/theme/components/slider.md)
* [Text](/docs/theme/components/text.md)
* [Video](/docs/theme/components/video.md)
