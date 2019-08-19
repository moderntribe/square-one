<?php

namespace Tribe\Project\Templates\Components;

class Dialog extends Component {

	const TEMPLATE_NAME = 'components/dialog.twig';

	const CONTENT = 'content';

	protected function parse_options( array $options ): array {
		$defaults = [
			static::CONTENT => '',
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::CONTENT => $this->options[ 'content' ],
		];

		return $data;
	}
}
