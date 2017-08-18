<?php

namespace Tribe\Project\Templates\Components;

class Quote extends Component {

	const TEMPLATE_NAME = 'components/quote.twig';

	const QUOTE       = 'quote';
	const CITE        = 'cite';
	const CLASSES     = 'classes';
	const QUOTE_ATTRS = 'quote_attrs';
	const CITE_ATTRS  = 'cite_attrs';

	protected function parse_options( array $options ): array {
		$defaults = [
			static::QUOTE       => '',
			static::CITE        => '',
			static::CLASSES     => [],
			static::QUOTE_ATTRS => [],
			static::CITE_ATTRS  => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			'quote'       => $this->options[ static::QUOTE ],
			'cite'        => $this->options[ static::CITE ],
			'classes'     => $this->merge_classes( [ 'c-quote' ], $this->options[ static::CLASSES ], true ),
			'quote_attrs' => $this->merge_attrs( [], $this->options[ static::QUOTE_ATTRS ], true ),
			'cite_attrs'  => $this->merge_attrs( [], $this->options[ static::CITE_ATTRS ], true ),
		];

		return $data;
	}
}
