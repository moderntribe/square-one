# Image Component

The Image component is one of the more feature rich components within Square 1. This can and should be used for almost any situation where you need to get and display an image from the WordPress Media Library.

This component has these features out of box:
* Full accessibility baked in.
* Support for the `srcset` and `sizes` attributes for displaying responsive images.
* Support for LazyLoading images including a custom shim image for pre-loading.
* Support for all standard image element attributes (src, alt, class, height, width, etc.) and custom attributes.
* Ability to fully control the image wrapper element, image element tag, image link
* Ability to echo the image as an image tag or to use it as a background image.
* Full support for live panel preview in panel instances, including in the repeater rows, sliders, posts lists and more.

### File Locations

* **Template:** `wp-content\themes\core\components\image.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\image.php`
* **PostCSS:** `wp-content\themes\core\pcss\components\_image.pcss`

### Options

#### `img_id`
* **Default:** _0_
* **Type:** _int_
* **Description:** Media Library Image ID

#### `as_bg`
* **Default:** _false_
* **Type:** _boolean_
* **Description:** Whether or not to set the image as a background image on the wrapper element.

#### `auto_shim`
* **Default:** _true_
* **Type:** _boolean_
* **Description:** If true, shim dir as set will be used, src_size will be used as filename, with png as filetype. Example: `themes\core\img\theme\shims\medium.png`

#### `auto_sizes_attr`
* **Default:** _false_
* **Type:** _boolean_
* **Description:** If lazyloading, the lib can auto create sizes attribute.

#### `component_class`
* **Default:** _''_
* **Type:** _string_
* **Description:** Custom class to apply to the component wrapper.

#### `echo`
* **Default:** _true_
* **Type:** _boolean_
* **Description:** Whether to echo or return the html or not.

#### `expand`
* **Default:** _200_
* **Type:** _int_
* **Description:** The expand attribute is the threshold used by lazysizes. Use a negative number to reveal once in viewport.

#### `html`
* **Default:** _''_
* **Type:** _string_
* **Description:** Append an html string in the wrapper.

#### `img_class`
* **Default:** _''_
* **Type:** _string_
* **Description:** Pass classes for image tag. If lazyload is true, the class, "lazyload", is auto added.

#### `img_attr`
* **Default:** _''_
* **Type:** _string_
* **Description:** Additional image attributes.

#### `img_alt_text`
* **Default:** _''_
* **Type:** _string_
* **Description:** Pass specific image alternate text. If not included, will default to image title.

#### `link`
* **Default:** _''_
* **Type:** _string_
* **Description:** Pass a URL to wrap the image.

#### `link_class`
* **Default:** _''_
* **Type:** _string_
* **Description:** Pass additional link classes.

#### `link_target`
* **Default:** __self_
* **Type:** _string_
* **Description:** pass a link target

#### `link_title`
* **Default:** _''_
* **Type:** _string_
* **Description:** Pass a link title.

#### `parent_fit`
* **Default:** _width_
* **Type:** _string_
* **Description:** If lazyloading, this combines with object fit css and the object fit polyfill.

#### `shim`
* **Default:** _''_
* **Type:** _string_
* **Description:** Supply a manually specified URL or image path to a shim for lazyloading. Will override auto_shim whether true/false.

#### `src`
* **Default:** _true_
* **Type:** _boolean_
* **Description:** Set to false to disable the src attribute. This is a fallback for non srcset browsers.

#### `src_size`
* **Default:** _large_
* **Type:** _string_
* **Description:** This is the main src registered image size. Use a predefined or custom registered WP image size.

#### `srcset_sizes`
* **Default:** _[]_
* **Type:** _array_
* **Description:** This is registered sizes array for srcset.

#### `srcset_sizes_attr`
* **Default:** _(min-width: 1260px) 1260px, 100vw_
* **Type:** _string_
* **Description:** This is the srcset sizes attribute string used if auto is false.

#### `use_h&w_attr`
* **Default:** _false_
* **Type:** _boolean_
* **Description:** This will set the width and height attributes on the img to be half the original for retina/hdpi. Only for not lazyloading and when src exists.

#### `use_lazyload`
* **Default:** _true_
* **Type:** _boolean_
* **Description:** Whether to use lazyloading or not.

#### `use_srcset`
* **Default:** _true_
* **Type:** _boolean_
* **Description:** Whether to use srcset or not.

#### `use_wrapper`
* **Default:** _true_
* **Type:** _boolean_
* **Description:** Use a wrapper tag around the image.

#### `wrapper_attr`
* **Default:** _''_
* **Type:** _string_
* **Description:** Additional wrapper attributes.

#### `wrapper_class`
* **Default:** _tribe-image_
* **Type:** _string_
* **Description:** Pass classes for the figure wrapper. If `as_bg` is set true, this gets an auto class of "lazyload".

#### `wrapper_tag`
* **Default:** _'figure'_
* **Type:** _string_
* **Description:** Specify HTML tag for the wrapper/background image container.


### Example Usage

An Card component twig file used in the CardGrid panel, being passed the 'image' var that was assembled by the panel controller using the card component.

```twig
<div class="{{ card_classes }}">

	{% if before_card %}
		{{ before_card }}
	{% endif %}

	{% if image %}
		<header class="{{ card_header_classes }}">
			{{ image }}
		</header>
	{% endif %}

	<div class="{{ card_content_classes }}">

		{% if pre_title %}
			{{ pre_title }}
		{% endif %}

		{% if title %}
			{{ title }}
		{% endif %}

		{% if post_title %}
			{{ post_title }}
		{% endif %}

		{% if text %}
			{{ text }}
		{% endif %}

		{% if button %}
			{{ button }}
		{% endif %}

	</div>

	{% if after_card %}
		{{ after_card }}
	{% endif %}

</div>
```

And CardGrid the panel controller using the image component within the card component.

```php
namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\CardGrid as CardGridPanel;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Card;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Title;
use Tribe\Project\Templates\Components\Text;
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
			'title' => $this->get_title( $this->panel_vars[ CardGridPanel::FIELD_TITLE ], [ 's-title', 'h2' ] ),
			'cards' => $this->get_cards(),
			'attrs' => $this->get_cardgrid_attributes(),
		];

		return $data;
	}

	protected function get_cards(): array {
		$cards = [];

		if ( ! empty( $this->panel_vars[ CardGridPanel::FIELD_CARDS ] ) ) {

			$i = 0;

			foreach ( $this->panel_vars[ CardGridPanel::FIELD_CARDS ] as $card ) {
				$options = [];

				if ( ! empty( $card[ CardGridPanel::FIELD_CARD_TITLE ] ) ) {
					$options[ Card::TITLE ] = $this->get_card_title( $card[ CardGridPanel::FIELD_CARD_TITLE ], $i );
				}

				if ( ! empty( $card[ CardGridPanel::FIELD_CARD_DESCRIPTION ] ) ) {
					$options[ Card::TEXT ] = $this->get_card_text( $card[ CardGridPanel::FIELD_CARD_DESCRIPTION ], $i );
				}

				if ( ! empty( $card[ CardGridPanel::FIELD_CARD_IMAGE ] ) ) {
					$options[ Card::IMAGE ] = $this->get_card_image( $card[ CardGridPanel::FIELD_CARD_IMAGE ] );
				}

				if ( ! empty( $card[ CardGridPanel::FIELD_CARD_CTA ] ) ) {
					$options[ Card::BUTTON ] = $this->get_card_button( $card[ CardGridPanel::FIELD_CARD_CTA ] );
				}

				$card_obj = Card::factory( $options );
				$cards[]  = $card_obj->render();

				$i ++;
			}
		}

		return $cards;
	}

	private function get_layout_container_attrs( $panel, $panel_object ): string {
		$data_attrs = sprintf( 'data-name="panels" data-depth="%s" data-livetext', esc_attr( $panel_object->get_depth() ) );

		return sprintf( '%s', $data_attrs );
	}

	protected function get_cardgrid_attributes() {
		$attrs = '';

		if ( is_panel_preview() ) {
			$attrs = 'data-depth=' . $this->panel->get_depth() . ' data-name="' . CardGridPanel::FIELD_CARDS . '" data-index="0" data-livetext="true"';
		}

		if ( empty( $attrs ) ) {
			return '';
		}

		return $attrs;
	}

	protected function get_card_image( $image_id ) {
		if ( empty( $image_id ) ) {
			return false;
		}

		$options = [
			Image::IMG_ID       => $image_id,
			Image::AS_BG        => false,
			Image::USE_LAZYLOAD => false,
			Image::ECHO         => false,
			Image::SRC_SIZE     => Image_Sizes::COMPONENT_CARD,
		];

		$image_obj = Image::factory( $options );

		return $image_obj->render();
	}

	protected function get_card_title( $title, $index ) {
		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs = [
				'data-depth'    => $this->panel->get_depth(),
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
				'data-depth'    => $this->panel->get_depth(),
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
			Button::CLASSES     => [ 'c-btn c-btn--sm' ],
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
