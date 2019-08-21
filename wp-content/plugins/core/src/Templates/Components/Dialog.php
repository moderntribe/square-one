<?php

namespace Tribe\Project\Templates\Components;

class Dialog extends Component {

	const TEMPLATE_NAME = 'components/dialog.twig';

	const CONTENT                 = 'content';
	const HEADER_CONTENT          = 'header_content';
	const DIALOG_ATTRS            = 'dialog_attr';
	const CONTENT_OVERLAY_CLASSES = 'content_overlay_classes';
	const CONTENT_HEADER_CLASSES  = 'content_header_classes';
	const CONTENT_WRAPPER_CLASSES = 'content_wrapper_classes';
	const CONTENT_INNER_CLASSES   = 'content_inner_classes';

	protected function parse_options( array $options ): array {
		$defaults = [
			static::CONTENT                 => '',
			static::HEADER_CONTENT          => '',
			static::DIALOG_ATTRS            => [],
			static::CONTENT_OVERLAY_CLASSES => [],
			static::CONTENT_HEADER_CLASSES  => [],
			static::CONTENT_WRAPPER_CLASSES => [],
			static::CONTENT_INNER_CLASSES   => [],

		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::CONTENT                 => $this->options[ 'content' ],
			static::HEADER_CONTENT          => $this->options[ 'header_content' ],
			static::DIALOG_ATTRS            => $this->merge_attrs( [], $this->options[ static::DIALOG_ATTRS ], true ),
			static::CONTENT_OVERLAY_CLASSES => $this->merge_classes( [ 'c-dialog__overlay' ], $this->options[ static::CONTENT_OVERLAY_CLASSES ], true ),
			static::CONTENT_HEADER_CLASSES  => $this->merge_classes( [ 'c-dialog__header' ], $this->options[ static::CONTENT_HEADER_CLASSES ], true ),
			static::CONTENT_WRAPPER_CLASSES => $this->merge_classes( [ 'c-dialog__content-wrapper' ], $this->options[ static::CONTENT_WRAPPER_CLASSES ], true ),
			static::CONTENT_INNER_CLASSES   => $this->merge_classes( [ 'c-dialog__content-inner' ], $this->options[ static::CONTENT_INNER_CLASSES ], true ),
		];

		return $data;
	}
}
