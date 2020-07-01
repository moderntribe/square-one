<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Text
 *
 * @property string   $content
 * @property string   $tag
 * @property string[] $classes
 * @property string[] $attrs
 */
class Text extends Component {

	public const TEXT    = 'content';
	public const TAG     = 'tag';
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';

	protected function defaults(): array {
		return [
			self::CLASSES => [],
			self::ATTRS   => [],
		];
	}

	public function render(): void {
		?>
        <{{ tag }} {{ classes|stringify }} {{ attrs|stringify }}>{{ content }}</{{ tag }}>
		<?php
	}
}
