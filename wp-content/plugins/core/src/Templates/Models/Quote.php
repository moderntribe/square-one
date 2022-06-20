<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models;

use Tribe\Libs\Field_Models\Field_Model;
use Tribe\Libs\Field_Models\Models\Image;

class Quote extends Field_Model {

	public string $quote_text = '';
	public string $cite_name  = '';
	public string $cite_title = '';
	public Image $cite_image;

}
