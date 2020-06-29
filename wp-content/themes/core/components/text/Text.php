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

	public function init() {
		$this->data[ self::CLASSES ] = $this->merge_classes( $this->data[ self::CLASSES ] ?? [] );
		$this->data[ self::ATTRS ]   = $this->merge_attrs( $this->data[ self::ATTRS ] ?? [] );
	}

	public function render(): void {
		?>
        <{{ tag }} {{ classes }} {{ attrs }}>{{ content }}</{{ tag }}>
		<?php
	}
}
