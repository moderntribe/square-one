<?php

namespace Tribe\Project\Components_Docs\Templates;

use Tribe\Project\Templates\Components\Component;

class Preview_Wrapper extends Component {

	const TEMPLATE_NAME = 'preview_wrapper.twig';

	const RENDERED  = 'rendered';

	public function parse_options( array $options ): array {
		$defaults = [
			self::RENDERED  => '',
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			self::RENDERED  => $this->options[ self::RENDERED ],
		];

		return $data;
	}

}