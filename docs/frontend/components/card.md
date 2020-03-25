# Card Component

A common layout, the Card component is used in loops, query panels etc quite often. It may or may not have an image along with headlines, text and cta/meta

This component has these features out of box:  

* before and after card strings
* optional image
* pre and post title strings
* title
* main text block
* cta button

### File Locations

* **Template:** `wp-content\themes\core\components\Card.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\Card.php`
* **PostCSS:** `wp-content\plugins\core\assets\theme\pcss\components\_card.pcss`

### Options

#### `card_classes` 
* **Default:** _'c-card'_ 
* **Type:** _string_ 
* **Description:** wrapper classes

#### `before_card` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** An html string to output before the card header. Typically used to wrap the whole card in a link.

#### `after_card` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** An html string to output after the card content. Typically used to wrap the whole card in a link.

#### `image` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** image html, probably composed with the image component

#### `card_header_classes` 
* **Default:** _'c-card__header'_ 
* **Type:** _string_ 
* **Description:** the classes for the card content container

#### `card_content_classes` 
* **Default:** _'c-card__content'_ 
* **Type:** _string_ 
* **Description:** the classes for the card content container

#### `pre_title` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** An html string to output before the title

#### `title` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** the title

#### `post_title` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** an html string to output after the title

#### `text` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** text/html string that is the body of the card

#### `button` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** button html, probably composed using the button component

### Example Usage

A Card Grid panel twig file, being passed the 'cards' var that was assembled by the panel controller using the card component.

```twig
{# Panel: Card Grid #}

{% extends "content/panels/panel.twig" %}

{% block content %}

	<div class="s-content">
		<div class="g-row g-row--center g-row--col-2--min-small g-row--col-reset--min-medium"
			 data-depth="0"
			 data-name="cards"
			 data-livetext>

			{% for card in cards %}
				<div class="g-col">
					{{ card }}
				</div>
			{% endfor %}

		</div>
	</div>

{% endblock %}

```

And the panel controller using the Card component

```php
<?php

namespace Tribe\Project\Templates\Controllers\Content\Panels;

use Tribe\Project\Panels\Types\CardGrid as CardGridPanel;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Card;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Components\Title;
use Tribe\Project\Theme\Image_Sizes;

class CardGrid extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'             => $this->get_title( CardGridPanel::FIELD_TITLE, [ 'site-section__title', 'h2' ] ),
			'description'       => ! empty( $this->panel_vars[ CardGridPanel::FIELD_DESCRIPTION ] ) ? $this->panel_vars[ CardGridPanel::FIELD_DESCRIPTION ] : false,
			'cards'             => $this->get_cards(),
		];

		return $data;
	}

	protected function get_cards(): array {
		$cards = [];

		if ( ! empty( $this->panel_vars[ CardGridPanel::FIELD_CARDS ] ) ) {

			$i = 0;

			foreach ( $this->panel_vars[ CardGridPanel::FIELD_CARDS ] as $card ) {
				$options = [
					Card::TITLE  => $this->get_card_title( $card[ CardGridPanel::FIELD_CARD_TITLE ], $i ),
					Card::TEXT   => $this->get_card_text( $card[ CardGridPanel::FIELD_CARD_DESCRIPTION ], $i ),
					Card::IMAGE  => $this->get_card_image( $card[ CardGridPanel::FIELD_CARD_IMAGE ] ),
					Card::BUTTON => $this->get_card_button( $card[ CardGridPanel::FIELD_CARD_CTA ] ),
				];

				$card_obj = Card::factory( $options );
				$cards[]  = $card_obj->render();

				$i ++;
			}
		}

		return $cards;
	}

	protected function get_card_image( $image_id ) {
		if ( empty( $image_id ) ) {
			return '';
		}

		try {
			$image = \Tribe\Project\Templates\Models\Image::factory( $image_id );
		} catch ( \Exception $e ) {
			return '';
		}

		$options = [
			Image::ATTACHMENT   => $image,
			Image::AS_BG        => false,
			Image::USE_LAZYLOAD => false,
			Image::SRC_SIZE     => Image_Sizes::COMPONENT_CARD,
		];

		$image_obj = $this->factory->get( Image::class, $options );

		return $image_obj->render();
	}

	protected function get_card_title( $title, $index ) {
		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs = [
				'data-depth'    => $this->get_depth(),
				'data-name'     => CardGridPanel::FIELD_CARD_TITLE,
				'data-index'    => $index,
				'data-livetext' => true,
			];
		}

		$options = [
			Title::TITLE   => $title,
			Title::CLASSES => [ 'c-card__title' ],
			Title::ATTRS   => $attrs,
			Title::TAG     => 'h3',
		];

		$title_obj = Title::factory( $options );

		return $title_obj->render();
	}

	protected function get_card_text( $text, $index ) {
		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs = [
				'data-depth'    => $this->get_depth(),
				'data-name'     => CardGridPanel::FIELD_CARD_DESCRIPTION,
				'data-index'    => $index,
				'data-livetext' => true,
			];
		}

		$options = [
			Text::TEXT    => $text,
			Text::CLASSES => [ 'c-card__desc' ],
			Text::ATTRS   => $attrs,
		];

		$text_obj = Text::factory( $options );

		return $text_obj->render();
	}

	protected function get_card_button( $cta ) {
		if ( empty( $cta[ Button::URL ] ) ) {
			return '';
		}

		$options = [
			Button::URL         => esc_url( $cta[ Button::URL ] ),
			Button::LABEL       => esc_html( $cta[ Button::LABEL ] ),
			Button::TARGET      => esc_attr( $cta[ Button::TARGET ] ),
			Button::BTN_AS_LINK => true,
			Button::CLASSES     => [ 'c-btn--sm' ],
		];

		$button_obj = Button::factory( $options );

		return $button_obj->render();
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/cardgrid'];
	}
}
```

## Table of Contents

* [Overview](/docs/frontend/components/README.md)
* [Accordion](/docs/frontend/components/accordion.md)
* [Button](/docs/frontend/components/button.md)
* [Card](/docs/frontend/components/card.md)
* [Content Block](/docs/frontend/components/content_block.md)
* [Image](/docs/frontend/components/image.md)
* [Quote](/docs/frontend/components/quote.md)
* [Slider](/docs/frontend/components/slider.md)
* [Tabs](/docs/frontend/components/tabs.md)
* [Template](/docs/frontend/components/template.md)
* [Text](/docs/frontend/components/text.md)
