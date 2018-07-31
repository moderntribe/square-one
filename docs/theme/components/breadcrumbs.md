# Breadcrumbs Component

The breadcrumbs component is string of hierarchical links indicating how deep you are with in a particular section of a site.

This component is essentially a husk. It simply wraps the contents of the items array you place in it.

Nothing is assumed here including the separator(s). To use this component properly, you'll need to provide all the things you wish to display here within each item in the array. Item properties are described below.

The example below only uses a single breadcrumb. You'll need to create your own list, possibly by getting post/page parent IDs, and adding those to the breadcrumb `$items[]`.

### File Locations

* **Template:** `wp-content\themes\core\components\breadcrumbs.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\Breadcrumbs.php`
* **PostCSS:** `wp-content\themes\core\pcss\components\_breadcrumbs.pcss`

### Options

#### `items`
* **Default:** _[]_
* **Type:** _array_
* **Description:** Array of items(links with labels, attrs, etc.) used to build the breadcrumbs list.

#### `wrapper_classes`
* **Default:** _\[ 'l-container', 'c-breadcrumbs__wrapper' ]_
* **Type:** _array_
* **Description:** Array of classes applied to the outer wrapper of the breadcrumbs container.

#### `wrapper_attrs`
* **Default:** _[]_
* **Type:** _array_
* **Description:** Array of attributes applied to the outer wrapper of the breadcrumbs container.

#### `main_classes`
* **Default:** _\[ 'c-breadcrumbs' ]_
* **Type:** _array_
* **Description:** Array of classes applied to the `<ul>` element wrapper of the breadcrumbs list.

#### `item_classes`
* **Default:** _\[ 'c-breadcrumbs__item' ]_
* **Type:** _array_
* **Description:** Array of classes applied to the `<li>` element of each item in the list.

#### `link_classes`
* **Default:** _\[ 'anchor', 'c-breadcrumbs__anchor' ]_
* **Type:** _array_
* **Description:** Array of classes applied to the `<a>` element of each item in the list.

#### `link_attrs`
* **Default:** _[]_
* **Type:** _array_
* **Description:** Array of attributes applied to the `<a>` element of each item in the list.

### Example Usage

An single post twig file, being passed the 'breadcrumbs' var that was assembled by the Index controller.

```twig
{% extends "base.twig" %}

{% block main %}

	{% block breadcrumbs %}
		{% if breadcrumbs %}
			{{ breadcrumbs }}
		{% endif %}
	{% endblock %}

	{% block subhead %}
		{{ include( 'content/header/sub.twig' ) }}
	{% endblock %}

	{% block content %}
		<div class="l-container">
			{{ include( 'content/single/post.twig') }}

			{{ include( 'content/pagination/single.twig') }}
		</div>

		{{ do_action( 'the_panels') }}
	{% endblock %}

	{{ comments }}


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

	protected function get_breadcrumbs() {

    		if ( ! is_archive() ) {
    			return '';
    		}

    		$news_url = get_permalink( get_option( 'page_for_posts' ) );

    		$items = [
    			[
    				'url'   => $news_url,
    				'label' => __( 'All News', 'tribe' ),
    			],
    		];

    		$options = [
    			Breadcrumbs::ITEMS => $items,
    		];

    		$crumbs = Breadcrumbs::factory( $options );

    		return $crumbs->render();
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
