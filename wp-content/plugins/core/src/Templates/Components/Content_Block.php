<?php

namespace Tribe\Project\Templates\Components;

class Content_Block extends Component {

	const TEMPLATE_NAME = 'components/contentblock.twig';

	const TITLE           = 'title';
	const TEXT            = 'text';
	const BUTTON          = 'button';
	const CLASSES         = 'classes';
	const CONTENT_CLASSES = 'content_classes';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::TITLE           => '',
			self::TEXT            => '',
			self::CLASSES         => [],
			self::CONTENT_CLASSES => [],
			self::BUTTON          => '',
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::TITLE           => $this->options[ self::TITLE ],
			static::CLASSES         => $this->merge_classes( [ 'c-content-block' ], $this->options[ self::CLASSES ], true ),
			static::CONTENT_CLASSES => $this->merge_classes( [ 'c-content-block__content' ], $this->options[ self::CONTENT_CLASSES ], true ),
			static::TEXT            => $this->options[ self::TEXT ],
			static::BUTTON          => $this->options[ self::BUTTON ],
		];

		return $data;
	}
}
