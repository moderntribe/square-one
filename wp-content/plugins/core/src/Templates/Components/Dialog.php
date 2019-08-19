<?php

namespace Tribe\Project\Templates\Components;

class Dialog extends Component {

	const TEMPLATE_NAME = 'components/dialog.twig';

	const CONTENT                 = 'content';
	const CONTENT_OVERLAY_CLASSES = 'content_overlay_classes';
	const CONTENT_WRAPPER_CLASSES = 'content_wrapper_classes';
	const CONTENT_INNER_CLASSES   = 'content_inner_classes';

	protected function parse_options( array $options ): array {
		$defaults = [
			static::CONTENT                 => '',
			static::CONTENT_OVERLAY_CLASSES => [],
			static::CONTENT_WRAPPER_CLASSES => [],
			static::CONTENT_INNER_CLASSES   => [],

		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::CONTENT                 => $this->options[ 'content' ],
			static::CONTENT_OVERLAY_CLASSES => $this->merge_classes( [ '' ], $this->options[ static::CONTENT_OVERLAY_CLASSES ], true ),
			static::CONTENT_WRAPPER_CLASSES => $this->merge_classes( [ '' ], $this->options[ static::CONTENT_WRAPPER_CLASSES ], true ),
			static::CONTENT_INNER_CLASSES   => $this->merge_classes( [ '' ], $this->options[ static::CONTENT_INNER_CLASSES ], true ),
		];

		return $data;
	}
}
