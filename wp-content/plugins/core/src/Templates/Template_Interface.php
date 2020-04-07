<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

interface Template_Interface {
	/**
	 * Build the data that will be available to the template
	 *
	 * @return array
	 */
	public function get_data(): array;

	/**
	 * Render the template and return it as a string
	 *
	 * @param string $path Path to the template to render, overriding any default that is set
	 *
	 * @return string The rendered template
	 */
	public function render( string $path = '' ): string;
}
