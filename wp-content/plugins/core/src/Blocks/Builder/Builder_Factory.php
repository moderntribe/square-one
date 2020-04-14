<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Builder;

class Builder_Factory extends \Tribe\Gutenpanels\Builder\Factories\Builder_Factory {
	public function block( string $name ): \Tribe\Gutenpanels\Builder\Block_Builder {
		return new Block_Builder( $name );
	}
}
