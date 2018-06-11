#  Button Component

The button component can be used wherever you need a button or link styled as a button.

This component has these features out of box:  

* Supports link or button tags, button is default. 
* Supports arbitrary attributes, classes, target and link href if in link mode 

### File Locations

* **Template:** `wp-content\themes\core\components\button.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\Button.php`
* **PostCSS:** `wp-content\plugins\core\assets\theme\pcss\components\button.pcss`

### Options

#### `btn_as_link` 
* **Default:** _true_ 
* **Type:** _bool_ 
* **Description:** Use link tag? Defaults to button

#### `attrs` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** Data atts, aria etc, eg 'data-js="nav-trigger" aria-controls="etc"'

#### `classes` 
* **Default:** _c-btn_ 
* **Type:** _string_ 
* **Description:** Additional classes for the button/link, icon classes etc

#### `label` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** The button or link text, wrapped in a span with class of `c-btn__text`

#### `target` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** If type is link you can pass in target

#### `type` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** If tag is button, you can set type, eg `submit`

#### `url` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** If type is link you can set url here

### Example Usage

An Micro Navigation Button panel twig file, being passed the 'items' var that was assembled by the panel controller using the  button component for part of its markup.

```twig
{# Panel: Micro Navigation Buttons #}

{% extends "content/panels/panel.twig" %}

{% block content %}

	<div class="s-content">
		<div class="g-row">
			<div class="g-row--col-2--min-medium g-row--col-4--min-full"
			     data-depth="0"
			     data-name="items"
			     data-livetext>

				{% for item in items %}
					<div class="g-col g-col--horizontal-center">
						{{ item }}
					</div>
				{% endfor %}

			</div>
		</div>
	</div>

{% endblock %}

```

And the panel controller using the Button component

```php
<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\MicroNavButtons as Micro;
use Tribe\Project\Templates\Components\Button;

class MicroNavButtons extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'       => $this->get_title( Micro::FIELD_TITLE, [ 'site-section__title', 'h2' ] ),
			'description' => ! empty( $this->panel_vars[ Micro::FIELD_DESCRIPTION ] ) ? $this->panel_vars[ Micro::FIELD_DESCRIPTION ] : false,
			'items'       => $this->get_list_items(),
		];

		return $data;
	}

	protected function get_list_items(): array {
		$btns = [];

		if ( ! empty( $this->panel_vars[ Micro::FIELD_ITEMS ] ) ) {
			for ( $i = 0; $i < count( $this->panel_vars[ Micro::FIELD_ITEMS ] ); $i ++ ) {

				$btn = $this->panel_vars[ Micro::FIELD_ITEMS ][ $i ];

				$options = [
					Button::URL     => esc_url( $btn[ Micro::FIELD_ITEM_CTA ]['url'] ),
					Button::TARGET  => esc_attr( $btn[ Micro::FIELD_ITEM_CTA ]['target'] ),
					Button::LABEL   => esc_attr( $btn[ Micro::FIELD_ITEM_CTA ]['label'] ),
					Button::CLASSES => [ 'c-btn--full c-btn--lg' ],
				];

				$btn_obj = Button::factory( $options );
				$btns[]  = $btn_obj->render();
			}
		}

		return $btns;
	}

	public static function instance() {
			return tribe_project()->container()['twig.templates.content/panels/micronavbuttons'];
		}
}

```

## Table of Contents

* [Overview](/docs/theme/components/README.md)
* [Accordion](/docs/theme/components/accordion.md)
* [Button](/docs/theme/components/button.md)
* [Card](/docs/theme/components/card.md)
* [Content Block](/docs/theme/components/content_block.md)
* [Quote](/docs/theme/components/quote.md)
* [Slider](/docs/theme/components/slider.md)
* [Template](/docs/theme/components/template.md)
* [Text](/docs/theme/components/text.md)
