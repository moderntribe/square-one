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

	protected function defaults(): array {
		return [
			self::WRAPPER_CLASSES => [],
			self::WRAPPER_ATTRS   => [],
			self::MAIN_CLASSES    => [],
			self::ITEM_CLASSES    => [],
			self::LINK_CLASSES    => [],
			self::LINK_ATTRS      => [],
		];
	}

	public function render(): void {
		?>
        <div {{ wrapper_classes|stringify }} {{ wrapper_attrs|stringify }}>
            <ul {{ main_classes|stringify }}>

                {% for item in items %}
                <li {{ item_classes|stringify }}>
                    <a href="{{ item.url }}" {{ link_classes|stringify }} {{ link_attrs|stringify }}>
                        {{ item.label }}
                    </a>
                </li>
                {% endfor %}
            </ul>
        </div>
		<?php
	}
}
