<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Content\Header;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Components\Header\Subheader as Subheader_Context;

class Subheader extends Abstract_Template {
	protected $path = 'content/header/sub.twig';

	public function render( string $path = '' ): string {
		return $this->factory->get( Subheader_Context::class, [
			Subheader_Context::TITLE => get_the_title(),
		] )->render( $path );
	}

}
