<?php

namespace Tribe\Project\Templates\Components;

class Search extends Component {

	protected $path = 'components/search.twig';

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
			self::FORM_CLASSES  => [],
			self::FORM_ATTRS    => [],
			self::LABEL_CLASSES => [],
			self::LABEL_ATTRS   => [],
			self::LABEL_TEXT    => [],
			self::INPUT_CLASSES => [],
			self::INPUT_ATTRS   => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			self::FORM_CLASSES  => $this->merge_classes( [], $this->options[ self::FORM_CLASSES ], true ),
			self::FORM_ATTRS    => $this->merge_attrs( [], $this->options[ self::FORM_ATTRS ], true ),
			self::LABEL_CLASSES => $this->merge_classes( [], $this->options[ self::LABEL_CLASSES ], true ),
			self::LABEL_ATTRS   => $this->merge_attrs( [], $this->options[ self::LABEL_ATTRS ], true ),
			self::LABEL_TEXT    => $this->merge_classes( [], $this->options[ self::LABEL_TEXT ], true ),
			self::INPUT_CLASSES => $this->merge_classes( [], $this->options[ self::INPUT_CLASSES ], true ),
			self::INPUT_ATTRS   => $this->merge_attrs( [], $this->options[ self::INPUT_ATTRS ], true ),
			self::SUBMIT_BUTTON => $this->options[ self::SUBMIT_BUTTON ],
		];

		return $data;
	}
}
