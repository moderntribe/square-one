<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Sidebar
 */
class Sidebar extends Component {
	public const SIDEBAR_ID = 'sidebar_id';
	public const ACTIVE     = 'active';
	public const CONTENT    = 'content';

	public function init() {
		if ( ! isset( $this->data['sidebar_id'] ) ) {
			return;
		}

		$this->data['active']  = is_active_sidebar( $this->data['sidebar_id'] );
		$this->data['content'] = $this->get_content();
	}

	public function get_content() {
		ob_start();
		dynamic_sidebar( $this->data['sidebar_id'] );

		return ob_get_clean();
	}

}
