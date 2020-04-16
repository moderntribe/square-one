<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

interface Controller_Interface {
	/**
	 * Render the relevant components to a string
	 *
	 * @param string $path A template path to pass to the component's context
	 *
	 * @return string The rendered template
	 * @throws \Exception
	 */
	public function render( string $path = '' ): string;
}
