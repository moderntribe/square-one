#  Tabs Component

The tabs component displays an accessible tabbing module with tablist and tab panels. By default the first button is selected. You should not need to touch the javascript file


### File Locations

* **Template:** `wp-content\themes\core\components\tabs.twig`
* **Controller:** `wp-content\plugins\core\src\Templates\Components\Tabs.php`
* **Javascript:** `wp-content\plugins\core\assets\theme\js\src\components\tabs.js`
* **PostCSS:** `wp-content\plugins\core\assets\theme\pcss\components\tabs.pcss`

### Options

#### `container_classes` 
* **Default:** _['c-tabs']_
* **Type:** _array_ 
* **Description:** outer wrapper classes for the entire component
#### `tab_id` 
* **Default:** _tab-XXX_ 
* **Type:** _string_ 
* **Description:** Id for outer wrapper. Generated from uniqid()
#### `container_attrs` 
* **Default:** _[]_ 
* **Type:** _array_ 
* **Description:** the main container attributes as key value pair
#### `tab_list_classes` 
* **Default:** _['c-tab__list']_
* **Type:** _array_ 
* **Description:** tab list classes
#### `tab_list_attrs` 
* **Default:** _[]_ 
* **Type:** _array_ 
* **Description:** the tab list attributes as key value pair
#### `tab_button_classes` 
* **Default:** _[ 'c-tab__button' ]_ 
* **Type:** _array_ 
* **Description:** the button classes
#### `tab_button_active_class`
* **Default:** _c-tab__button--active_ 
* **Type:** _string_ 
* **Description:** the button active class
#### `tab_button_attrs`
* **Default:** _[]_ 
* **Type:** _array_ 
* **Description:** Base button attributes as key value pair
#### `tab_content_classes`
* **Default:** _[ 'c-tab__content' ]_ 
* **Type:** _array_ 
* **Description:** Tab content wrapper classes
#### `tab_content_attrs`
* **Default:** _[]_ 
* **Type:** _array_ 
* **Description:** Tab content attributes as key value pair
#### `tab_content_inner_classes`
* **Default:** _[ 'c-tab__content-inner' ]_ 
* **Type:** _array_ 
* **Description:** Tab content inner classes
#### `tab_content_active_class`
* **Default:** _c-tab__content--active_ 
* **Type:** _string_ 
* **Description:** the content active class
#### `tabs` 
* **Default:** _[]_ 
* **Type:** _array_ 
* **Description:** tabs details... see below
#### `tabs[].content`
* **Default:**  
* **Type:** _string_ 
* **Description:** The content. HTML or what have you.
#### `tabs[].content_id`
* **Default:**  
* **Type:** _string_ 
* **Description:** Unique id for the content.
#### `tabs[].tab_text`
* **Default:**  
* **Type:** _string_ 
* **Description:** The tab text
#### `tabs[].tab_id`
* **Default:**  
* **Type:** _string_ 
* **Description:** Unique id for the tab.
#### `tabs[].tab_attrs`
* **Default:** _[]_ 
* **Type:** _array_ 
* **Description:** Additional button attributes. Passed into the buttons component so they must be an array of key value pairs
#### `tabs[].content_attrs`
* **Default:**  
* **Type:** _string_ 
* **Description:** Additional content attributes as a string

### Example Usage

A gallery panel twig file, being passed the 'slider' var that was assembled by the panel controller using the slider component.

```twig
{# Panel: Tabs #}

{% extends "content/panels/panel.twig" %}

{% block content %}

	<div class="s-content">
		<div class="g-row"
			 data-depth="0"
			 data-name="tabs"
			 data-livetext
		>
			<div class="g-col">
				{{ tabs }}
			</div>
		</div>
	</div>

{% endblock %}
```

The panel controller must assemble the individual tabs and set up the tab and content ids. Pass in the live update attributes if necessary.

```php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Tabs as TabsPanel;
use Tribe\Project\Templates\Components\Tabs as TabsComponent;
use Tribe\Project\Theme\Util;

class Tabs extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'        => $this->get_title( $this->panel_vars[ TabsPanel::FIELD_TITLE ], [ 'section__title' ] ),
			'tabs'         => $this->get_tabs(),
		];

		return $data;
	}

	protected function get_tabs(): string {
		$options = [
			TabsComponent::TABS => $this->get_rows(),
			TabsComponent::TAB_LIST_ATTRS => [
				'data-depth' => $this->panel->get_depth(),
				'data-name' => 'tabs',
				'data-livetext' => 1
			]
		];

		$tabs = TabsComponent::factory( $options );

		return $tabs->render();
	}

	protected function get_rows(): array {
		$rows = $this->panel_vars[ TabsPanel::FIELD_TABS];
		if ( empty( $rows ) ) {
			return [];
		}
		return array_map( function ( $row, $index ) {
			$content_attrs = ( !is_preview() ) ? '' : Util::array_to_attributes([
				'data-depth'    => $this->panel->get_depth(),
				'data-index'    => $index,
				'data-name'     => 'row_content',
				'data-autop'    => 'true',
				'data-livetext' => 1
			]);
			$btn_attrs = ( !is_preview() ) ? [] : [
				'data-depth'    => $this->panel->get_depth(),
				'data-index'    => $index,
				'data-name'     => 'row_header',
				'data-livetext' => 1
			];
			return [
				'tab_id'        => uniqid( 'tabs-header-' ),
				'content_id'    => uniqid( 'tabs-content-' ),
				'tab_text'      => $row[ TabsPanel::FIELD_TABS_TITLE ],
				'content'       => $row[ TabsPanel::FIELD_TABS_CONTENT ],
				'content_attrs' => $content_attrs,
				'btn_attrs'     => $btn_attrs
			];
		}, $rows, array_keys($rows) );
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/tabs'];
	}
}

```

## Table of Contents

* [Overview](/docs/frontend/components/README.md)
* [Tabs](/docs/frontend/components/tabs.md)
* [Button](/docs/frontend/components/button.md)
