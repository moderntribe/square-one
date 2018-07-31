# Title Component

The title component is exactly what it sounds like. A Title.

This component is very similar to the [Text](/docs/theme/components/text.md) component with one key difference. You can specify the wrapper tag on the title component whereas with the text component, you are restricted to using a `<div>`.

### File Locations

* **Template:** `wp-content\themes\core\components\title.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\Title.php`
* **PostCSS:** `wp-content\themes\core\pcss\components\_title.pcss`

### Options

#### `tag`
* **Default:** _''_
* **Type:** _string_
* **Description:** The tag element to use as the title wrapper. Example: `h1`;

#### `title`
* **Default:** _''_
* **Type:** _string_
* **Description:** The title string.

#### `classes`
* **Default:** _[]_
* **Type:** _array_
* **Description:** An array of classes applied to the `tag` element.

#### `attrs`
* **Default:** _[]_
* **Type:** _array_
* **Description:** An array of attributes applied to the `tag` element.

### Example Usage

An ImageText panel twig file, being passed the 'content_block' var that was assembled by the panel controller using the [Content Block](/components_docs/content_block) component which incorporates the title component.

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

And the panel controller using the title component

```php
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

	protected function get_image_text_title( $title_attrs ) {
		$options = [
			Title::CLASSES => [ 'h2' ],
			Title::TAG     => 'h2',
			Title::ATTRS   => $title_attrs,
			Title::TITLE   => esc_html( $this->panel_vars[ ImageTextPanel::FIELD_TITLE ] ),
		];

		$title_object = Title::factory( $options );

		return $title_object->render();
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
