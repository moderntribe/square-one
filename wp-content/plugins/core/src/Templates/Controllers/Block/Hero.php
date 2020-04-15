<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

use Tribe\Project\Blocks\Types\Accordion as Accordion_Block;
use Tribe\Project\Blocks\Types\Accordion_Section as Accordion_Section_Block;
use Tribe\Project\Templates\Components\Accordion as Accordion_Component;
use Tribe\Project\Templates\Components\Panels\Accordion as Accordion_Context;

class Hero extends Block_Controller {
	public function render( string $path = '' ): string {
		return '<pre>' . print_r( $this->attributes, true ) . '</pre>';
	}
}
