<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Card
 *
 * @property string   $before_card
 * @property string   $after_card
 * @property array   $title
 * @property array   $text
 * @property array   $image
 * @property string   $pre_title
 * @property string   $post_title
 * @property string[] $card_classes
 * @property string[] $card_header_classes
 * @property string[] $card_content_classes
 * @property array   $button
 */
class Card extends Component {

	public const BEFORE_CARD     = 'before_card';
	public const AFTER_CARD      = 'after_card';
	public const TITLE           = 'title';
	public const TEXT            = 'text';
	public const IMAGE           = 'image';
	public const PRE_TITLE       = 'pre_title';
	public const POST_TITLE      = 'post_title';
	public const CARD_CLASSES    = 'card_classes';
	public const HEADER_CLASSES  = 'card_header_classes';
	public const CONTENT_CLASSES = 'card_content_classes';
	public const BUTTON          = 'button';

	protected function defaults(): array {
		return [
			self::BEFORE_CARD     => '',
			self::AFTER_CARD      => '',
			self::TITLE           => [],
			self::TEXT            => [],
			self::IMAGE           => [],
			self::PRE_TITLE       => '',
			self::POST_TITLE      => '',
			self::CARD_CLASSES    => [ 'c-card' ],
			self::HEADER_CLASSES  => [ 'c-card__header' ],
			self::CONTENT_CLASSES => [ 'c-card__content' ],
			self::BUTTON          => [],
		];
	}

	public function render(): void {
		?>
		<div {{ card_classes }}>

			{% if before_card %}
				{{ before_card }}
			{% endif %}

			{% if image %}
				<header {{ card_header_classes }}>
					{{ component( 'image/Image.php', image ) }}
				</header>
			{% endif %}

			<div {{ card_content_classes }}>

				{% if pre_title %}
					{{ pre_title }}
				{% endif %}

				{% if title %}
					{{ component( 'text/Text.php', title ) }}
				{% endif %}

				{% if post_title %}
					{{ post_title }}
				{% endif %}

				{% if text %}
					{{ component( 'text/Text.php', text ) }}
				{% endif %}

				{% if button %}
					{{ component( 'button/Button.php', button ) }}
				{% endif %}

			</div>

			{% if after_card %}
				{{ after_card }}
			{% endif %}

		</div>
		<?php
	}

}
