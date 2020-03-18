<?php

namespace Tribe\Project\Templates\Components;

class Search extends Component {

	const TEMPLATE_NAME = 'components/search.twig';

	const FORM_CLASSES  = 'form_classes';
	const FORM_ATTRS    = 'form_attrs';
	const LABEL_CLASSES = 'label_classes';
	const LABEL_ATTRS   = 'label_attrs';
	const LABEL_TEXT    = 'label_text';
	const INPUT_CLASSES = 'input_classes';
	const INPUT_ATTRS   = 'input_attrs';
	const SUBMIT_BUTTON = 'submit_button';

	protected function parse_options( array $options ): array {
		$defaults = [
			static::FORM_CLASSES  => [],
			static::FORM_ATTRS    => [],
			static::LABEL_CLASSES => [],
			static::LABEL_ATTRS   => [],
			static::LABEL_TEXT    => [],
			static::INPUT_CLASSES => [],
			static::INPUT_ATTRS   => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::FORM_CLASSES  => $this->merge_classes( [], $this->options[ static::FORM_CLASSES ], true ),
			static::FORM_ATTRS    => $this->merge_attrs( [], $this->options[ self::FORM_ATTRS ], true ),
			static::LABEL_CLASSES => $this->merge_classes( [], $this->options[ static::LABEL_CLASSES ], true ),
			static::LABEL_ATTRS   => $this->merge_attrs( [], $this->options[ self::LABEL_ATTRS ], true ),
			static::LABEL_TEXT    => $this->merge_classes( [], $this->options[ static::LABEL_TEXT ], true ),
			static::INPUT_CLASSES => $this->merge_classes( [], $this->options[ static::INPUT_CLASSES ], true ),
			static::INPUT_ATTRS   => $this->merge_attrs( [], $this->options[ self::INPUT_ATTRS ], true ),
			static::SUBMIT_BUTTON => $this->options[ self::SUBMIT_BUTTON ],
		];

		return $data;
	}
}
