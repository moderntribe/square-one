<?php

namespace Tribe\Project\Templates\Components;

class Accordion extends Component {

	const TEMPLATE_NAME = 'components/accordion.twig';

	const ROWS                      = 'rows';
	const CLASSES                   = 'classes';
	const ATTRS                     = 'attrs';
	const ROW_CLASSES               = 'row_classes';
	const ROW_HEADER_CLASSES        = 'row_header_classes';
	const ROW_HEADER_INNER_CLASSES  = 'row_header_inner_classes';
	const ROW_CONTENT_CLASSES       = 'row_content_classes';
	const ROW_CONTENT_INNER_CLASSES = 'row_content_inner_classes';

	public function parse_options( array $options ): array {
		$defaults = [
			self::ROWS                      => [],
			self::CLASSES                   => [],
			self::ATTRS                     => [],
			self::ROW_CLASSES               => [],
			self::ROW_HEADER_CLASSES        => [],
			self::ROW_HEADER_INNER_CLASSES  => [],
			self::ROW_CONTENT_CLASSES       => [],
			self::ROW_CONTENT_INNER_CLASSES => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			'rows'                                => $this->options[ self::ROWS ],
			'accordion_attrs'                     => $this->merge_attrs( [ 'data-js' => 'c-accordion' ], $this->options[ self::ATTRS ], true ),
			'accordion_classes'                   => $this->merge_classes( [ 'c-accordion' ], $this->options[ self::CLASSES ], true ),
			'accordion_row_classes'               => $this->merge_classes( [ 'c-accordion__row' ], $this->options[ self::ROW_CLASSES ], true ),
			'accordion_row_header_classes'        => $this->merge_classes( [ 'c-accordion__header' ], $this->options[ self::ROW_HEADER_CLASSES ], true ),
			'accordion_row_header_inner_classes'  => $this->merge_classes( [ 'c-accordion__header-inner' ], $this->options[ self::ROW_HEADER_INNER_CLASSES ], true ),
			'accordion_row_content_classes'       => $this->merge_classes( [ 'c-accordion__content' ], $this->options[ self::ROW_CONTENT_CLASSES ], true ),
			'accordion_row_content_inner_classes' => $this->merge_classes( [ 'c-accordion__content-inner' ], $this->options[ self::ROW_CONTENT_INNER_CLASSES ], true ),
		];

		return $data;
	}
}