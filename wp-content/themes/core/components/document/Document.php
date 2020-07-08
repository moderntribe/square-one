<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Document;

use Tribe\Project\Components\Component;

class Document extends Component {

	public function init() {
		$this->data['language_attributes'] = $this->get_language_attributes();
		$this->data['body_class']          = $this->get_body_class();
	}

	protected function get_language_attributes() {
		ob_start();
		language_attributes();

		return ob_get_clean();
	}

	protected function get_body_class() {
		return implode( ' ', get_body_class() );
	}
}
