<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Container
 *
 * A component to contain arbitrary html and take classes/attributes. Useful when
 * composing more complex ui's in other controllers
 *
 * @property string   $content_component
 * @property array    $content
 * @property string   $tag
 * @property string[] $classes
 * @property string[] $attrs
 */
class Container extends Component {

	public const CONTENT_COMPONENT = 'content_component';
	public const CONTENT           = 'content';
	public const TAG               = 'tag';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	protected function defaults(): array {
		return [
			self::CONTENT_COMPONENT => 'content-block/Content_Block.php',
			self::CONTENT           => [],
			self::TAG               => 'div',
			self::CLASSES           => [ 'c-container' ],
			self::ATTRS             => [],
		];
	}

	public function render(): void {
		?>
		<{{ tag }} {{ classes|stringify }} {{ attrs|stringify }}>
			{{ component( content_component, content ) }}
		</{{ tag }}>
		<?php
	}
}
