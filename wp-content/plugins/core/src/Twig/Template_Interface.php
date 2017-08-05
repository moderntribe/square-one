<?php

namespace Tribe\Project\Twig;

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
	 * @return string THe rendered template
	 */
	public function render(): string;
}
