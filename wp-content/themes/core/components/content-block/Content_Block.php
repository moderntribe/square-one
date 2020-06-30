<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Content_Block
 *
 * @property string   $tag
 * @property string[] $classes
 * @property string[] $attrs
 * @property string[] $container_classes
 * @property array    $leadin
 * @property array    $title
 * @property array    $text
 * @property array    $action
 */
class Content_Block extends Component {

	public const TAG     = 'tag';
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';
	public const LEADIN  = 'leadin';
	public const TITLE   = 'title';
	public const TEXT    = 'text';
	public const ACTION  = 'action';

	protected function defaults(): array {
		return [
			self::TAG     => 'div',
			self::CLASSES => [ 'c-content-block' ],
			self::ATTRS   => [],
			self::LEADIN  => [],
			self::TITLE   => [],
			self::TEXT    => [],
			self::ACTION  => [],
		];
	}

	public function render(): void {
		?>
        <{{ tag }} {{ classes( classes ) }} {{ attributes( attrs ) }}>
        {{ component( 'text/Text.php', leadin ) }}
        {{ component( 'text/Text.php', title ) }}
        {{ component( 'text/Text.php', text ) }}
        {{ component( 'button/Button.php', action ) }}
        </{{ tag }}>
		<?php
	}
}
