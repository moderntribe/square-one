#   Component

The search component is fully functional search form complete with search field, label, and submit button.

This component has these features out of box:  

* Full accessibility baked in.
* Preconfigured to work with the main WP search system (or ElasticPress if this is setup on your site).
* Fully customizable and extensible using attributes to define standard and HTML5 field attributes.

### File Locations

* **Template:** `wp-content\themes\core\components\search.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\Search.php`
* **PostCSS:** `wp-content\themes\core\pcss\components\_search.pcss`

### Options

#### `form_classes`
* **Default:** _[]_
* **Type:** _array_
* **Description:** An array of classes applied to the `form` element wrapper.

#### `form_attrs`
* **Default:** _[]_
* **Type:** _array_
* **Description:** An array of attributes applied to the `form` element wrapper.

#### `label_classes`
* **Default:** _[]_
* **Type:** _array_
* **Description:** An array of classes applied to the `label` element of the input field.

#### `label_classes`
* **Default:** _[]_
* **Type:** _array_
* **Description:** An array of attributes applied to the `label` element of the input field.

#### `label_text`
* **Default:** _[]_
* **Type:** _array_
* **Description:** An array of strings injected into the `label` element.

#### `input_classes`
* **Default:** _[]_
* **Type:** _array_
* **Description:** An array of classes applied to the `label` element of the input field.

#### `input_attrs`
* **Default:** _[]_
* **Type:** _array_
* **Description:** An array of attributes applied to the `label` element of the input field.

#### `submit_button`
* **Default:** _N/A_
* **Type:** _N/A_
* **Description:** This component requires you to pass a [Button](/component_docs/button) component to this option.

### Example Usage

The header twig file, being passed the 'search' var that was assembled by the Base controller using the search component.

```twig
<header class="site-header">

	<div class="l-container">

		{{ logo }}

		{{ include( 'content/navigation/header.twig' ) }}

		{{ search }}

	</div>

</header>

```

And the Base controller using the Search component

```php
class Base extends Twig_Template {

	public function get_data(): array {
		$data = [
			'page_title'          => $this->get_page_title(),
			'body_class'          => $this->get_body_class(),
			'logo'                => $this->get_logo(),
			'menu'                => $this->get_nav_menus(),
			'search_url'          => $this->get_search_url(),
			'home_url'            => $this->get_home_url(),
			'copyright'           => $this->get_copyright(),
			'language_attributes' => $this->get_language_attributes(),
			'search'              => $this->get_search(),
		];

		foreach ( $this->get_components() as $component ) {
			$data = array_merge( $data, $component->get_data() );
		}

		return $data;
	}

	...

	protected function get_search(): string {
		$get_submit_button = $this->submit_button();

		$form_attrs = [
			'role'   => 'search',
			'method' => 'get',
			'action' => esc_url( get_home_url() ),
		];

		$label_attrs = [
			'for' => 's',
		];

		$input_attrs = [
			'type' => 'text',
			'id'   => 's',
			'name' => 's',
		];

		$options = [
			Search::FORM_CLASSES  => [ 'c-search' ],
			Search::FORM_ATTRS    => $form_attrs,
			Search::LABEL_CLASSES => [ 'c-search__label' ],
			Search::LABEL_ATTRS   => $label_attrs,
			Search::LABEL_TEXT    => [ 'Search' ],
			Search::INPUT_CLASSES => [ 'c-search__input' ],
			Search::INPUT_ATTRS   => $input_attrs,
			Search::SUBMIT_BUTTON => $get_submit_button,
		];

		$search = Search::factory( $options );

		return $search->render();
	}

	protected function submit_button(): string {

		$btn_attr = [
			'type'  => 'submit',
			'name'  => 'submit',
			'value' => __( 'Search', 'tribe' ),
		];

		$options = [
			Button::LABEL   => __( 'Search', 'tribe' ),
			Button::CLASSES => [ 'c-button' ],
			Button::ATTRS   => $btn_attr,
		];

		$button = Button::factory( $options );

		return $button->render();
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
