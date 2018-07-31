#   Component

The pagination component is a component intended to be used on templates and archives containing loops or paged posts.

This component is intended to be used in conjunction with the `Pagination_Util` class. This utility sets up paged post data automatically and passes to correct data to the to page links array that you'll pass to the Pagination component.

This component has these features out of box:  

* Support for paged loops of posts or data using a custom query or `$wp_query`.
* Ability to turn on/off prev/next links. (Used from within `Pagination_Util`)
* Ability to turn on/off first and last page links. (Used from within `Pagination_Util`)
* Ability to decide the number of links surrounding the current page. (Used from within `Pagination_Util`)
* Ability to use ellipses to separate larger page gaps. (Used from within `Pagination_Util`)

### File Locations

* **Template:** `wp-content\themes\core\components\pagination.twig`
* **Controller 1:** `wp-content\plugins\core\src\Templates\Components\Pagination.php`
* **Controller 2:** `wp-content\plugins\core\src\Theme\Pagination_Util.php`
* **PostCSS:** `wp-content\themes\core\pcss\components\_pagination.pcss`

### Options

#### `wrapper_classes`
* **Default:** _[]_
* **Type:** _array_
* **Description:** Array of classes applied to the outermost `<nav>` element for this component.

#### `wrapper_attrs`
* **Default:** _[]_
* **Type:** _array_
* **Description:** Array of attributes applied to the outermost `<nav>` element for this component.

#### `list_classes`
* **Default:** _[]_
* **Type:** _array_
* **Description:** Array of classes applied to the nav list `<ul>` element.

#### `list_attrs`
* **Default:** _[]_
* **Type:** _array_
* **Description:** Array of attributes applied to the nav list `<ul>` element.

#### `list_item_classes`
* **Default:** _[]_
* **Type:** _array_
* **Description:** Array of classes applied to each `<li>` element in the nav list.

#### `list_item_attrs`
* **Default:** _[]_
* **Type:** _array_
* **Description:** Array of attributes applied to each `<li>` element in the nav list.

#### `first_post`
* **Default:** _''_
* **Type:** _string_
* **Description:** Link to use as the first post/page link in the nav list. This is a static persistent item.

#### `last_post`
* **Default:** _''_
* **Type:** _string_
* **Description:** Link to use as the last post/page link in the nav list. This is a static persistent item.

#### `prev_post`
* **Default:** _''_
* **Type:** _string_
* **Description:** Link to use as the previous post/page link in the nav list.

#### `next_post`
* **Default:** _''_
* **Type:** _string_
* **Description:** Link to use as the next post/page link in the nav list.

#### `pagination_numbers`
* **Default:** _''_
* **Type:** _string_
* **Description:** This is the array of links used to display paged number links in the pagination nav list.

### Example Usage

The main Index archive template twig file, being passed the 'pagination' var that was assembled by the Index controller using the pagination component.

```twig
{% extends "base.twig" %}

{% block main %}

	{% block subhead %}
		{{ include( 'content/header/sub.twig' ) }}
	{% endblock %}

	<div class="l-container">

		{% if posts|length > 0 %}

			{% for post in posts %}
				{{ include( 'content/loop/results.twig' ) }}
			{% endfor %}

			{% if pagination %}
				{{ pagination }}
			{% endif %}

		{% else %}

			{{ include( 'content/no-results.twig' ) }}

		{% endif %}

	</div>

{% endblock %}

```

And the panel controller using the  component

```php
class Index extends Base {

	public function get_data(): array {
		$data                = parent::get_data();
		$data['posts']       = $this->get_posts();
		$data['page_num']    = $this->get_current_page();
		$data['breadcrumbs'] = $this->get_breadcrumbs();
		$data['pagination']  = $this->get_pagination();

		return $data;
	}

	...

	public function get_pagination(): string {
		$links = $this->get_pagination_numbers();

		$options = [
			Pagination::LIST_CLASSES       => [ 'g-row', 'g-row--no-gutters', 'c-pagination__list' ],
			Pagination::LIST_ITEM_CLASSES  => [ 'g-col', 'c-pagination__item' ],
			Pagination::WRAPPER_CLASSES    => [ 'c-pagination', 'c-pagination--loop' ],
			Pagination::WRAPPER_ATTRS      => [ 'aria-labelledby' => 'c-pagination__label-single' ],
			Pagination::PAGINATION_NUMBERS => $links,
		];

		return Pagination::factory( $options )->render();
	}

	public function get_pagination_numbers(): array {
		$links = [];

		$pagination = new Pagination_Util();
		$numbers = $pagination->numbers( 2, true, false, false );

		if ( empty( $numbers ) ) {
			return $links;
		}

		foreach ( $numbers as $number ) {

			if ( $number['active'] ) {
				$number['classes'][] = 'active';
			}

			if ( $number['prev'] ) {
				$number['classes'][] = 'icon icon-cal-arrow-left';
			}

			if ( $number['next'] ) {
				$number['classes'][] = 'icon icon-cal-arrow-right';
			}

			$options = [
				Button::CLASSES     => $number['classes'],
				Button::URL         => $number['url'],
				Button::LABEL       => $number['label'],
				Button::BTN_AS_LINK => true,
			];

			$links[] = Button::factory( $options )->render();
		}

		return $links;
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
* [Pagination](/docs/theme/components/pagination.md)