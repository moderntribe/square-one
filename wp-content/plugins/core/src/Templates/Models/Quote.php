<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models;

use Tribe\Libs\Field_Models\Field_Model;

class Quote extends Field_Model {

	public string $quote_text = '';
	public Citation $citation;

}
