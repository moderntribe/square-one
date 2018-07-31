# Slider Component

The slider component is a [Swiper JS](http://idangero.us/swiper/) powered slider that can be used wherever you need a slider, from content, to quotes, complex recipes to basic image galleries or galleries with carousels. Out of box on square one it's being used to power the Testimonials Panel, the Hero Slider Panel, the Gallery Panel and the content Post Gallery shortcode. If you need to extend the template functionality consider bringing that back to SquareOne.

You should not need to touch the javascript file, unless you need really custom behavior, in which case you should create a new js file for that and use a different data-js attribute to harvest the element. That said, you can pass an attribute of `data-swiper-options='{"speed":600}'` which can take any of the arguments in the swiper [api](http://idangero.us/swiper/api/) to modify its behavior without touching the js.

This component has these features out of box:  

* Arguments for enabling or disabling pagination, arrows, a synchronized carousel.
* Accessibility turned on by default
* Post gallery support baked in and enabled. To disable turn off the shortcode override.

### File Locations

* **Template:** `wp-content\themes\core\components\slider.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\Slider.php`
* **Javascript:** `wp-content\themes\core\js\src\components\slider.js`
* **PostCSS:** `wp-content\themes\core\pcss\components\slider.pcss`

### Options

#### `carousel_attrs` 
* **Default:** _data-js="c-slider-carousel_ 
* **Type:** _string_ 
* **Description:** the carousel sliders data attributes
#### `container_classes` 
* **Default:** _c-slider_ 
* **Type:** _string_ 
* **Description:** outer wrapper classes for the entire component
#### `main_attrs` 
* **Default:** _data-js="c-slider"_ 
* **Type:** _string_ 
* **Description:** the main sliders data attributes
#### `main_classes` 
* **Default:** _c-slider__main swiper-container_ 
* **Type:** _string_ 
* **Description:** the main slider classes. modified based on show_carousel, show_arrows and show_pagination args in controller as well
#### `show_arrows` 
* **Default:** _true_ 
* **Type:** _bool_ 
* **Description:** show arrows on main slider?
#### `show_carousel` 
* **Default:** _true_ 
* **Type:** _bool_ 
* **Description:**  show thumbnail carousel linked to main slider?
#### `show_pagination` 
* **Default:** _false_ 
* **Type:** _bool_ 
* **Description:** show the pagination bullets?
#### `slides` 
* **Default:** _[]_ 
* **Type:** _array_ 
* **Description:** slide html, images, what have you for the main slider
#### `slide_classes` 
* **Default:** _c-slider__slide swiper-slide_ 
* **Type:** _string_ 
* **Description:** the main sliders individual slide classes
#### `thumbnails` 
* **Default:** _[]_ 
* **Type:** _array_ 
* **Description:** Array of thumbnail html for the carousel
#### `wrapper_classes` 
* **Default:** _c-slider__wrapper swiper-wrapper_ 
* **Type:** _string_ 
* **Description:** the main sliders slide wrapper classes

### Example Usage

A gallery panel twig file, being passed the 'slider' var that was assembled by the panel controller using the slider component.

```twig
{# Panel: Gallery #}

{% extends "content/panels/panel.twig" %}

{% block content %}

	<div class="s-content">
		<div class="g-row">

			{{ slider }}

		</div>
	</div>

{% endblock %}
```

And the panel controller using the slider component plus the image component to assemble an image gallery with carousel. Whether to letterbox or crop and showing carousel or not are panel options, and we see how to pass those to the component as well.

```php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Gallery as GalleryPanel;
use Tribe\Project\Templates\Components\Image as ImageComponent;
use Tribe\Project\Templates\Components\Slider as SliderComponent;

class Gallery extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'  => $this->get_title( GalleryPanel::FIELD_TITLE, [ 'section__title' ] ),
			'slider' => $this->get_slider(),
		];

		return $data;
	}

	protected function get_slider(): string {
		$options = [
			SliderComponent::SLIDES          => $this->get_slides(),
			SliderComponent::THUMBNAILS      => $this->get_slides( 'thumbnail' ),
			SliderComponent::SHOW_CAROUSEL   => $this->show_carousel(),
			SliderComponent::SHOW_ARROWS     => true,
			SliderComponent::SHOW_PAGINATION => true,
			SliderComponent::MAIN_CLASSES    => $this->get_slider_main_classes(),
			SliderComponent::MAIN_ATTRS      => [ 'data-swiper-options' => '{"speed":600}' ],
			SliderComponent::CAROUSEL_ATTRS  => [ 'data-swiper-options' => '{"speed":600}' ],
		];

		$slider = SliderComponent::factory( $options );

		return $slider->render();
	}

	public function show_carousel(): bool {
		$show_carousel = true;

		if ( ! empty( $this->panel_vars[ GalleryPanel::FIELD_CAROUSEL ] ) && $this->panel_vars[ GalleryPanel::FIELD_CAROUSEL ] == GalleryPanel::FIELD_CAROUSEL_HIDE ) {
			$show_carousel = false;
		}

		return $show_carousel;
	}

	public function use_crop(): bool {
		$use_crop = true;

		if ( ! empty( $this->panel_vars[ GalleryPanel::FIELD_IMAGE_TREATMENT ] ) && $this->panel_vars[ GalleryPanel::FIELD_IMAGE_TREATMENT ] == GalleryPanel::FIELD_IMAGE_TREATMENT_OPTION_LETTERBOX ) {
			$use_crop = false;
		}

		return $use_crop;
	}

	protected function get_slides( $size = 'full' ): array {
		$slide_ids = $this->panel_vars[ GalleryPanel::FIELD_GALLERY ];

		if ( empty( $slide_ids ) ) {
			return [];
		}

		return array_map( function ( $slide_id ) use ( $size ) {
			$options = [
				'img_id'       => $slide_id,
				'as_bg'        => $this->use_crop() && $size == 'full',
				'use_lazyload' => false,
				'echo'         => false,
				'src_size'     => $size,
			];

			$image = ImageComponent::factory( $options );

			return $image->render();
		}, $slide_ids );
	}

	protected function get_slider_main_classes() {
		$classes = [ sprintf( 'c-slider__main--%s', $this->panel_vars[ GalleryPanel::FIELD_IMAGE_TREATMENT ] ) ];

		return $classes;
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
