# Text Component

Bare-bones text/html component to be used wherever you print text or html.

### File Locations

* **Template:** `wp-content\themes\core\components\text.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\Text.php`
* **PostCSS:** `wp-content\themes\core\pcss\components\_text.pcss`

### Options

#### `classes` 
* **Default:** _''_
* **Type:** _string_ 
* **Description:** The wrapper classes.

#### `attrs` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** Data attributes for the text block. You'll want to pass in livetext attrs if using in panels.

#### `content` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** Text/html.

### Example Usage

An ImageText panel twig file, being passed the 'content_block' var that was assembled by the panel controller using the [Content Block](/docs/theme/components/content_block.md) component which incorporates the text component.

```twig
{% extends "content/panels/panel.twig" %}

{% block start %}
<section class="panel s-wrapper s-wrapper--no-padding site-panel site-panel--{{ type|esc_attr }}"
		 data-index="{{ index }}"
		 data-js="panel"
		 data-type="{{ type|esc_attr }}"
		 data-modular-content>
    {% endblock %}

{% block wrapper_start %}{% endblock %}

	{% block header %}{% endblock %}

	{% block content %}

		<div class="g-row g-row--no-gutters g-row--col-2--min-full {{ wrapper_classes }}">

			<div class="g-col">
				{{ image }}
			</div>

			<div class="g-col g-col--vertical-center">
				<div class="t-content site-panel__imgtxt-content">
					{{ content_block }}
				</div>
			</div>

		</div>

	{% endblock %}

{% block wrapper_end %}{% endblock %}
```

And the ImageText panel controller using the text component within the content block component.

```php
class ImageText extends Panel {

	...

	protected function get_mapped_panel_data(): array {

		$data = [
			'wrapper_classes' => $this->get_panel_classes(),
			'image'           => $this->get_panel_image(),
			'content_block'   => $this->get_content_block(),
		];

		return $data;
	}

	protected function get_content_block() {

		$title_attrs       = [];
		$description_attrs = [];

		if ( is_panel_preview() ) {

			$title_attrs = [
				'data-depth'    => 0,
				'data-name'     => ImageTextPanel::FIELD_TITLE,
				'data-livetext' => true,
			];

			$description_attrs = [
				'data-depth'    => 0,
				'data-name'     => ImageTextPanel::FIELD_DESCRIPTION,
				'data-autop'    => 'true',
				'data-livetext' => true,
			];
		}

		$options = [
			Content_Block::TITLE           => $this->get_image_text_title( $title_attrs ),
			Content_Block::CLASSES         => '',
			Content_Block::BUTTON          => $this->get_image_text_button(),
			Content_Block::CONTENT_CLASSES => '',
			Content_Block::TEXT            => $this->get_image_text_text( $description_attrs ),
		];

		$content_block_obj = Content_Block::factory( $options );

		return $content_block_obj->render();
	}

	...

	protected function get_image_text_text( $description_attrs ) {
    		$options = [
    			Text::ATTRS   => $description_attrs,
    			Text::CLASSES => '',
    			Text::TEXT    => $this->panel_vars[ ImageTextPanel::FIELD_DESCRIPTION ],
    		];

    		$text_object = Text::factory( $options );

    		return $text_object->render();
    	}

	...

```

## Table of Contents

* [Overview](/docs/theme/components/README.md)
* [Accordion](/docs/theme/components/accordion.md)
* [Breadcrumbs](/docs/theme/components/breadcrumbs.md)
* [Button](/docs/theme/components/button.md)
* [Card](/docs/theme/components/card.md)
* [Content Block](/docs/theme/components/content_block.md)
* [Image](/docs/theme/components/Image.md)
* [Pagination](/docs/theme/components/pagination.md)
* [Quote](/docs/theme/components/quote.md)
* [Search](/docs/theme/components/search.md)
* [Slider](/docs/theme/components/slider.md)
* [Text](/docs/theme/components/text.md)
* [Title](/docs/theme/components/title.md)
* [Video](/docs/theme/components/video.md)
