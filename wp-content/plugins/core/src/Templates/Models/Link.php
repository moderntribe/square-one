<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models;

use Tribe\Libs\Field_Models\Field_Model;
use Tribe\Libs\Field_Models\Models\Cta;

class Link extends Field_Model {

	public string $link_header  = '';
	public string $link_content = '';
	public Cta $cta;

}
