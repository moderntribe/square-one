<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Content;

use Tribe\Project\Components\Component;
use Tribe\Project\Templates\Components\Share;

class Single extends Component {

	public const POST  = 'post';
	public const SHARE = 'social_share';

	public function init() {
		$this->data[ self::SHARE ] = $this->get_social_share();
	}

	public function get_social_share() {
		return [
			Share::LABELED => true,
		];
	}
}
