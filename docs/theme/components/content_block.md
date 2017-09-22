# Content Block Component

A component comprised of the title, text and button components. These are all optional, consider this a parent component for a common pattern. Note the button component is used in link mode and considered a cta here. Also note the agrs shown here are not the ones that you see in the twig file, but what the controller needs to produce the block. We may revisit this blocks architecture...

### File Locations

* **Template:** `wp-content\themes\core\components\content_block.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\Content_Block.php`
* **PostCSS:** `wp-content\plugins\core\assets\theme\pcss\components\_content-block.pcss`

### Options

#### `classes` 
* **Default:** _c-content-block_ 
* **Type:** _string_ 
* **Description:** The wrapper classes

#### `cta_classes` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** The buttons - in cta mode - additional classes

#### `content_classes` 
* **Default:** _c-content-block__content_ 
* **Type:** _string_ 
* **Description:** The content classes for the title, text and button wrapper

#### `cta` 
* **Default:** _[]_ 
* **Type:** _array_ 
* **Description:** The array that contains the cta url, label and target

#### `url` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:**  Lives in the cta array. The url.

#### `label` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** Lives in the cta array. The link text

#### `target` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** Lives in the cta array The link target

#### `text` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** The text/html string that makes up the main content area

#### `text_attrs` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** Data attributes for the text block. You'll want to pass in livetext attrs if using in panels

#### `text_classes` 
* **Default:** _c-content-block__desc_ 
* **Type:** _string_ 
* **Description:** Classes for the wrapper on the text block

#### `title` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** The title string

#### `title_attrs` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** Data attributes on title. You'll want to pass in livetext attrs if using in panels

#### `title_classes` 
* **Default:** _c-content-block__title_ 
* **Type:** _string_ 
* **Description:** Classes on title

#### `title_tag` 
* **Default:** _''_ 
* **Type:** _string_ 
* **Description:** The tag for the title (h1, h2 etc)

### Example Usage

An hero panel twig file, being passed the 'content_block' var that was assembled by the panel controller using the content_block component.

```twig
{# Panel: Hero #}

{% extends "content/panels/panel.twig" %}

{% block header %}{% endblock %}

{% block wrapper_start %}{% endblock %}

	{% block content %}

		<div class="l-container u-vertical-padding">
			<div class="g-row g-row--vertical-center">
				<div class="t-content">
					{{ content_block }}
				</div>
			</div>
		</div>

	{% endblock %}

{% block wrapper_end %}{% endblock %}

```

And the panel controller using the  component

```php
<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Hero as HeroPanel;
use Tribe\Project\Templates\Components\Content_Block;

class Hero extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'content_block' => $this->get_content_block(),
		];

		return $data;
	}

	protected function get_content_block() {

		$title_attrs       = [];
		$description_attrs = [];

		if ( is_panel_preview() ) {

			$title_attrs = [
				'data-depth'    => $this->panel->get_depth(),
				'data-name'     => esc_attr( HeroPanel::FIELD_TITLE ),
				'data-livetext' => true,
			];

			$description_attrs = [
				'data-depth'    => $this->panel->get_depth(),
				'data-name'     => esc_attr( HeroPanel::FIELD_DESCRIPTION ),
				'data-autop'    => 'true',
				'data-livetext' => true,
			];
		}

		$options = [
			Content_Block::TITLE       => $this->panel_vars[ HeroPanel::FIELD_TITLE ],
			Content_Block::TEXT        => $this->panel_vars[ HeroPanel::FIELD_DESCRIPTION ],
			Content_Block::CTA         => $this->panel_vars[ HeroPanel::FIELD_CTA ],
			Content_Block::TITLE_ATTRS => $title_attrs,
			Content_Block::TEXT_ATTRS  => $description_attrs,
			Content_Block::TITLE_TAG   => 'h1',
		];

		$content_block_obj = Content_Block::factory( $options );

		return $content_block_obj->render();
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/hero'];
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
