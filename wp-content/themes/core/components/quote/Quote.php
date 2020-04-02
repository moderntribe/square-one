<?php

namespace Tribe\Project\Templates\Components;

class Quote extends Component {

	protected $path = __DIR__ . '/quote.twig';

	const QUOTE       = 'quote';
	const CITE        = 'cite';
	const CLASSES     = 'classes';
	const QUOTE_ATTRS = 'quote_attrs';
	const CITE_ATTRS  = 'cite_attrs';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::QUOTE       => '',
			self::CITE        => '',
			self::CLASSES     => [],
			self::QUOTE_ATTRS => [],
			self::CITE_ATTRS  => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			self::QUOTE       => $this->options[ self::QUOTE ],
			self::CITE        => $this->options[ self::CITE ],
			self::CLASSES     => $this->merge_classes( [ 'c-quote' ], $this->options[ self::CLASSES ], true ),
			self::QUOTE_ATTRS => $this->merge_attrs( [], $this->options[ self::QUOTE_ATTRS ], true ),
			self::CITE_ATTRS  => $this->merge_attrs( [], $this->options[ self::CITE_ATTRS ], true ),
		];

		return $data;
	}
}
