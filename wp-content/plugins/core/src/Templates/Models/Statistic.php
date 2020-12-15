<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Models;

class Statistic {
	public string $value;
	public string $label;

	/**
	 * Statistic constructor.
	 *
	 * @param string $value
	 * @param string $label
	 */
	public function __construct( string $value, string $label ) {
		$this->value = $value;
		$this->label = $label;
	}
}
