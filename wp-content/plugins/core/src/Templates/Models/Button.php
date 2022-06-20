<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models;

use Tribe\Libs\Field_Models\Field_Model;
use Tribe\Libs\Field_Models\Models\Cta;

class Button extends Field_Model {

	/**
	 * The button type set in the block.
	 */
	public string $button_style = '';
	public Cta $cta;

}
