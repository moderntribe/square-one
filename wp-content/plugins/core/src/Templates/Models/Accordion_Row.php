<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models;

use Tribe\Libs\Field_Models\Field_Model;

class Accordion_Row extends Field_Model {

	public string $row_header  = '';
	public string $row_content = '';

}
