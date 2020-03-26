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
	 * @return string The rendered template
	 */
	public function render(): string;
}
