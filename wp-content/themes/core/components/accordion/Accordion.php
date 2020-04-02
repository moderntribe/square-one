<?php

namespace Tribe\Project\Templates\Components;

class Accordion extends Component {

	const TEMPLATE_NAME = 'components/accordion.twig';

	const ROWS                      = 'rows';
	const CONTAINER_CLASSES         = 'container_classes';
	const CONTAINER_ATTRS           = 'container_attrs';
	const ROW_CLASSES               = 'row_classes';
	const ROW_HEADER_CLASSES        = 'row_header_classes';
	const ROW_HEADER_INNER_CLASSES  = 'row_header_inner_classes';
	const ROW_CONTENT_CLASSES       = 'row_content_classes';
	const ROW_CONTENT_INNER_CLASSES = 'row_content_inner_classes';
	const ROW_HEADER_NAME           = 'row_header_name';
	const ROW_CONTENT_NAME          = 'row_content_name';

	public function parse_options( array $options ): array {
		$defaults = [
			self::ROWS                      => [],
			self::CONTAINER_CLASSES         => [],
			self::CONTAINER_ATTRS           => [],
			self::ROW_CLASSES               => [],
			self::ROW_HEADER_CLASSES        => [],
			self::ROW_HEADER_INNER_CLASSES  => [],
			self::ROW_CONTENT_CLASSES       => [],
			self::ROW_CONTENT_INNER_CLASSES => [],
			self::ROW_HEADER_NAME           => 'title',
			self::ROW_CONTENT_NAME          => 'row_content',
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			self::ROWS                      => $this->options[self::ROWS],
			self::CONTAINER_ATTRS           => $this->merge_attrs( [ 'data-js' => 'c-accordion' ], $this->options[self::CONTAINER_ATTRS], true ),
			self::CONTAINER_CLASSES         => $this->merge_classes( [ 'c-accordion' ], $this->options[self::CONTAINER_CLASSES], true ),
			self::ROW_CLASSES               => $this->merge_classes( [ 'c-accordion__row' ], $this->options[self::ROW_CLASSES], true ),
			self::ROW_HEADER_CLASSES        => $this->merge_classes( [ 'c-accordion__header' ], $this->options[self::ROW_HEADER_CLASSES], true ),
			self::ROW_HEADER_INNER_CLASSES  => $this->merge_classes( [ 'c-accordion__header-inner' ], $this->options[self::ROW_HEADER_INNER_CLASSES], true ),
			self::ROW_CONTENT_CLASSES       => $this->merge_classes( [ 'c-accordion__content' ], $this->options[self::ROW_CONTENT_CLASSES], true ),
			self::ROW_CONTENT_INNER_CLASSES => $this->merge_classes( [ 'c-accordion__content-inner' ], $this->options[self::ROW_CONTENT_INNER_CLASSES], true ),
			self::ROW_HEADER_NAME           => $this->options[self::ROW_HEADER_NAME],
			self::ROW_CONTENT_NAME          => $this->options[self::ROW_CONTENT_NAME],
		];

		return $data;
	}
}