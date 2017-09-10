# Card Component

A common layout, the Card component is used in loops, query panels etc quite often. It may or may not have an image along with headlines, text and cta/meta

This component has these features out of box:  

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

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\CardGrid as CardGridPanel;
use Tribe\Project\Templates\Components\Card;

class CardGrid extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'       => $this->get_title( CardGridPanel::FIELD_TITLE, [ 'site-section__title', 'h2' ] ),
			'description' => ! empty( $this->panel_vars[ CardGridPanel::FIELD_DESCRIPTION ] ) ? $this->panel_vars[ CardGridPanel::FIELD_DESCRIPTION ] : false,
			'cards'       => $this->get_the_cards(),
		];

		return $data;
	}

	protected function get_the_cards(): array {
		$cards = [];

		if ( ! empty( $this->panel_vars[ CardGridPanel::FIELD_CARDS ] ) ) {

			$i = 0;

			foreach ( $this->panel_vars[ CardGridPanel::FIELD_CARDS ] as $card ) {

				$title_attrs       = [];
				$description_attrs = [];

				if ( is_panel_preview() ) {
					$title_attrs = [
						'data-depth'    => $this->panel->get_depth(),
						'data-name'     => CardGridPanel::FIELD_CARD_TITLE,
						'data-index'    => $i,
						'data-livetext' => true,
					];

					$description_attrs = [
						'data-depth'    => $this->panel->get_depth(),
						'data-name'     => CardGridPanel::FIELD_CARD_DESCRIPTION,
						'data-index'    => $i,
						'data-autop'    => 'true',
						'data-livetext' => true,
					];
				}

				$options = [
					Card::TITLE       => $card[ CardGridPanel::FIELD_CARD_TITLE ],
					Card::TEXT        => $card[ CardGridPanel::FIELD_CARD_DESCRIPTION ],
					Card::IMAGE       => $card[ CardGridPanel::FIELD_CARD_IMAGE ],
					Card::CTA         => $card[ CardGridPanel::FIELD_CARD_CTA ],
					Card::TITLE_ATTRS => $title_attrs,
					Card::TEXT_ATTRS  => $description_attrs,
				];

				$card_obj = Card::factory( $options );
				$cards[]  = $card_obj->render();

				$i ++;
			}
		}

		return $cards;
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/cardgrid'];
	}
}
```

### Table of Contents

* [Overview](/docs/theme/components/README.md)
* [Accordion](/docs/theme/components/accordion.md)
* [Button](/docs/theme/components/button.md)
* [Card](/docs/theme/components/card.md)
* [CTA](/docs/theme/components/cta.md)
* [Follow](/docs/theme/components/follow.md)
* [Image](/docs/theme/components/image.md)
* [Quote](/docs/theme/components/quote.md)
* [Share](/docs/theme/components/share.md)
* [Slider](/docs/theme/components/slider.md)
* [Text](/docs/theme/components/text.md)
* [Video](/docs/theme/components/video.md)
