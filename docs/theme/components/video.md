#   Component

The Video component is a lightweight and load-time-conscious component. Videos are initially displayed with customizable play buttons and poster images. When clicked, these buttons will load the video inline and begin playing the video.

This component has these features out of box:  

* Partial accessibility baked in. (We're not able to strictly require subtitles or other accessibility features at this time.)
* FE load efficiency with the use of links + lazyloaded image placeholders that only load the video once they're clicked.
* Placeholder image triggers allow for full video description via the `<figcaption>` element.

### File Locations

* **Template:** `wp-content\themes\core\components\video.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\Video.php`
* **Javascript:** `wp-content\themes\core\js\src\theme\modules\embeds.js`
* **PostCSS:** `wp-content\themes\core\pcss\components\_video.pcss`

### Options

#### `title`
* **Default:** _''_
* **Type:** _string_
* **Description:** Title applied to the title attribute of the triggering anchor tag.

#### `video_url`
* **Default:** _''_
* **Type:** _string_
* **Description:** Full URL to the video that will be embeded.

#### `thumbnail_url`
* **Default:** _''_
* **Type:** _string_
* **Description:** Full URL src to the image that will be used as a poster/placeholder for this video.

#### `play_text`
* **Default:** ___( 'Play Video', 'tribe' )_
* **Type:** _string_
* **Description:** Text used to display with the play button describing the video or action to take.

#### `shim`
* **Default:** _trailingslashit( get_stylesheet_directory_uri() ) . 'img/theme/shims/16x9.png'_
* **Type:** _string_
* **Description:** Full URL src to the shim image that will be used as the lazyloading placeholder for this video.

#### `container_classes`
* **Default:** _[ 'c-video' ]_
* **Type:** _array_
* **Description:** Array of classes that will be applied to the outermost wrapper element.

#### `container_attrs`
* **Default:** _[]_
* **Type:** _array_
* **Description:** Array of attributes that will be applied to the outermost wrapper element.

#### `container_wrap_classes`
* **Default:** _[ 'c-video__wrapper' ]_
* **Type:** _array_
* **Description:** Array of classes that will be applied to the element wrapping the figure element.

#### `figure_classes`
* **Default:** _[ 'c-video__embed' ]_
* **Type:** _array_
* **Description:** Array of classes that will be applied to the figure wrapper (the innermost) element.

### Example Usage

A VideoText panel twig file, being passed the 'video' var that was assembled by the panel controller using the video component.

```twig
{% extends "content/panels/panel.twig" %}

{% block header %}{% endblock %}

{% block wrapper_start %}{% endblock %}

	{% block content %}

		<div class="g-row g-row--no-gutters g-row--col-2--min-full {{ wrapper_classes }}">

			<div class="g-col">
				{{ video }}
			</div>

			<div class="g-col g-col--vertical-center">
				<div class="site-panel__videotxt-content">
					{{ content_block }}
				</div>
			</div>

		</div>

	{% endblock %}

{% block wrapper_end %}{% endblock %}
```

The VideoText controller using the Video component

```php
class VideoText extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	protected function get_mapped_panel_data(): array {

		$data = [
			'wrapper_classes' => $this->get_panel_classes(),
			'video'           => $this->get_panel_video(),
			'content_block'   => $this->get_content_block(),
		];

		return $data;
	}

	protected function get_panel_video() {

    		$options = [
    			Video::VIDEO_URL => esc_html( $this->panel_vars[ VideoTextPanel::FIELD_VIDEO ] ),
    		];

    		$video = Video::factory( $options );

    		return $video->render();
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
* [Image](/docs/theme/components/Image.md)
* [Breadcrumbs](/docs/theme/components/breadcrumbs.md)
* [Search](/docs/theme/components/search.md)
* [Title](/docs/theme/components/title.md)
* [Video](/docs/theme/components/video.md)
