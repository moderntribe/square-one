<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Breadcrumbs
 *
 * @property string[] $items
 * @property string[] $wrapper_classes
 * @property string[] $wrapper_attrs
 * @property string[] $main_classes
 * @property string[] $item_classes
 * @property string[] $link_classes
 * @property string[] $link_attrs
 */
class Breadcrumbs extends Component {
	public const ITEMS           = 'items';
	public const WRAPPER_CLASSES = 'wrapper_classes';
	public const WRAPPER_ATTRS   = 'wrapper_attrs';
	public const MAIN_CLASSES    = 'main_classes';
	public const ITEM_CLASSES    = 'item_classes';
	public const LINK_CLASSES    = 'link_classes';
	public const LINK_ATTRS      = 'link_attrs';

	public function init() {
		$this->data['wrapper_classes'] = $this->merge_classes( $this->data['wrapper_classes'] ?? [] );
		$this->data['wrapper_attrs']   = $this->merge_attrs( $this->data['wrapper_attrs'] ?? [] );

		$this->data['main_classes'] = $this->merge_classes( $this->data['main_classes'] ?? [] );
		$this->data['item_classes'] = $this->merge_classes( $this->data['item_classes'] ?? [] );

		$this->data['link_classes'] = $this->merge_classes( $this->data['link_classes'] ?? [] );
		$this->data['link_attrs']   = $this->merge_attrs( $this->data['link_attrs'] ?? [] );
	}

	public function render(): void {
		?>
		<div {{ wrapper_classes }} {{ wrapper_attrs }}>
			<ul {{ main_classes }}>

				{% for item in items %}
				<li {{ item_classes }}>
					<a href="{{ item.url }}" {{ link_classes }} {{ link_attrs }}>
						{{ item.label }}
					</a>
				</li>
				{% endfor %}
			</ul>
		</div>
		<?php
	}
}
