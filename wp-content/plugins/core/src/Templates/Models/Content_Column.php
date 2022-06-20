<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models;

use Tribe\Libs\Field_Models\Field_Model;
use Tribe\Libs\Field_Models\Models\Cta;

class Content_Column extends Field_Model {

	public Cta $cta;
	public string $col_title   = '';
	public string $col_content = '';

}
