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
			static::QUOTE       => $this->options[ static::QUOTE ],
			static::CITE        => $this->options[ static::CITE ],
			static::CLASSES     => $this->merge_classes( [ 'c-quote' ], $this->options[ static::CLASSES ], true ),
			static::QUOTE_ATTRS => $this->merge_attrs( [], $this->options[ static::QUOTE_ATTRS ], true ),
			static::CITE_ATTRS  => $this->merge_attrs( [], $this->options[ static::CITE_ATTRS ], true ),
		];

		return $data;
	}
}
