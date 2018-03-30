# Quote Component

A simple quote component to be used wherever you need a quote, with an optional cite attribute

### File Locations

* **Template:** `wp-content\themes\core\components\quote.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\Quote.php`
* **PostCSS:** `wp-content\plugins\core\assets\theme\pcss\components\_quote.pcss`

### Options

#### `classes` 
* **Default:** _'c-quote'_ 
* **Type:** _string_ 
* **Description:** The wrapper classes

#### `quote` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** The quote string

#### `quote_attrs` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** Data attributes for the quote block. You'll want to pass in livetext attrs if using in panels

#### `cite` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** The optional cite

#### `cite_attrs` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** Data attributes for cite block. You'll want to pass in livetext attrs if using in panels

### Example Usage

A testimonial panel twig file, being passed the 'slider' var that was assembled by the panel controller using the quote component as the renderer for the slides array in the slider component component.

```twig
{# Panel: Testimonial #}

{% extends "content/panels/panel.twig" %}

{% block wrapper_start %}
	<div class="site-panel--testimonial__inner {{ text_color }}">
{% endblock %}

{% block content %}

	<div class="s-content">
		<div class="g-row">
			{{ slider }}
		</div>
	</div>

{% endblock %}

```

And the panel controller using the quote component for the contents of the slider component

```php
<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Testimonial as TestimonialPanel;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Quote;
use Tribe\Project\Templates\Components\Slider;

class Testimonial extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'      => $this->get_title( TestimonialPanel::FIELD_TITLE, [ 'site-section__title', 'h5' ] ),
			'text_color' => $this->text_color(),
			'slider'     => $this->get_slider(),
		];

		return $data;
	}

	protected function get_slider(): string {
		$options = [
			Slider::SLIDES          => $this->get_slides(),
			Slider::SHOW_CAROUSEL   => false,
			Slider::SHOW_ARROWS     => false,
			Slider::SHOW_PAGINATION => true,
			Slider::MAIN_CLASSES    => $this->get_slider_main_classes(),
		];

		$slider = Slider::factory( $options );

		return $slider->render();
	}

	protected function get_slides(): array {
		$quotes = [];

		if ( ! empty( $this->panel_vars[ TestimonialPanel::FIELD_QUOTES ] ) ) {
			for ( $i = 0; $i < count( $this->panel_vars[ TestimonialPanel::FIELD_QUOTES ] ); $i ++ ) {

				$quote       = $this->panel_vars[ TestimonialPanel::FIELD_QUOTES ][ $i ];
				$quote_attrs = [];
				$cite_attrs  = [];

				if ( is_panel_preview() ) {
					$quote_attrs = [
						'data-depth'    => $this->panel->get_depth(),
						'data-name'     => TestimonialPanel::FIELD_QUOTE,
						'data-index'    => $i,
						'data-autop'    => 'true',
						'data-livetext' => true,
					];

					$cite_attrs = [
						'data-depth'    => $this->panel->get_depth(),
						'data-name'     => TestimonialPanel::FIELD_CITE,
						'data-index'    => $i,
						'data-livetext' => true,
					];
				}

				$options = [
					Quote::QUOTE       => $quote[ TestimonialPanel::FIELD_QUOTE ],
					Quote::CITE        => $quote[ TestimonialPanel::FIELD_CITE ],
					Quote::QUOTE_ATTRS => $quote_attrs,
					Quote::CITE_ATTRS  => $cite_attrs,
				];

				$quote_obj = Quote::factory( $options );
				$quotes[]  = $quote_obj->render();
			}
		}

		return $quotes;
	}

	protected function text_color() {

		$classes = [];

		if ( TestimonialPanel::FIELD_TEXT_LIGHT === $this->panel_vars[ TestimonialPanel::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-content--light';
		}

		if ( TestimonialPanel::FIELD_TEXT_DARK === $this->panel_vars[ TestimonialPanel::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-content--dark';
		}

		return implode( ' ', $classes );
	}

	protected function get_slider_main_classes() {
		$classes = [ 'c-slider__main' ];

		return $classes;
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/testimonial'];
	}
}
```

### Table of Contents

* [Overview](/docs/theme/components/README.md)
* [Accordion](/docs/theme/components/accordion.md)
* [Button](/docs/theme/components/button.md)
* [Card](/docs/theme/components/card.md)
* [Content Block](/docs/theme/components/content_block.md)
* [Quote](/docs/theme/components/quote.md)
* [Slider](/docs/theme/components/slider.md)
* [Template](/docs/theme/components/template.md)
* [Text](/docs/theme/components/text.md)
