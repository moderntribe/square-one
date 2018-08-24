<?php

namespace Tribe\Project\Templates\Components;

class Card extends Component {

	const TEMPLATE_NAME = 'components/card.twig';

	const BEFORE_CARD     = 'before_card';
	const AFTER_CARD      = 'after_card';
	const TITLE           = 'title';
	const TEXT            = 'text';
	const IMAGE           = 'image';
	const PRE_TITLE       = 'pre_title';
	const POST_TITLE      = 'post_title';
	const CLASSES         = 'card_classes';
	const HEADER_CLASSES  = 'card_header_classes';
	const CONTENT_CLASSES = 'card_content_classes';
	const BUTTON          = 'button';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::BEFORE_CARD     => '',
			self::AFTER_CARD      => '',
			self::TITLE           => '',
			self::TEXT            => '',
			self::IMAGE           => '',
			self::PRE_TITLE       => '',
			self::POST_TITLE      => '',
			self::CLASSES         => [],
			self::HEADER_CLASSES  => [],
			self::CONTENT_CLASSES => [],
			self::BUTTON          => '',
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::BEFORE_CARD     => $this->options[ self::BEFORE_CARD ],
			static::AFTER_CARD      => $this->options[ self::AFTER_CARD ],
			static::TITLE           => $this->options[ self::TITLE ],
			static::PRE_TITLE       => $this->options[ self::PRE_TITLE ],
			static::POST_TITLE      => $this->options[ self::POST_TITLE ],
			static::CLASSES         => $this->merge_classes( [ 'c-card' ], $this->options[ self::CLASSES ], true ),
			static::HEADER_CLASSES  => $this->merge_classes( [ 'c-card__header' ], $this->options[ self::HEADER_CLASSES ], true ),
			static::CONTENT_CLASSES => $this->merge_classes( [ 'c-card__content' ], $this->options[ self::CONTENT_CLASSES ], true ),
			static::TEXT            => $this->options[ self::TEXT ],
			static::IMAGE           => $this->options[ self::IMAGE ],
			static::BUTTON          => $this->options[ self::BUTTON ],
		];

		return $data;
	}
}