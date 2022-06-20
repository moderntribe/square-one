<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models;

use Tribe\Libs\Field_Models\Field_Model;

class Social_Link extends Field_Model {

	public string $class = '';
	public string $url   = '';
	public string $title = '';

}
