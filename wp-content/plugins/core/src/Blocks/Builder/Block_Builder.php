<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Builder;

use Tribe\Gutenpanels\Blocks\Attributes\Render_Callback_Strategy;
use Tribe\Gutenpanels\Render\Views\Apply_Filters_Strategy;

class Block_Builder extends \Tribe\Gutenpanels\Builder\Block_Builder {
	protected function build_render_strategy(): Render_Callback_Strategy {
		return new Render_Callback_Strategy( $this->render_strategy ?: new Apply_Filters_Strategy() );
	}
}
