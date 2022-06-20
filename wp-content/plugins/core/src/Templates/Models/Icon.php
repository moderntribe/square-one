<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models;

use Tribe\Libs\Field_Models\Field_Model;
use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Libs\Field_Models\Models\Image;

class Icon extends Field_Model {

	public Image $icon_image;
	public Cta $cta;
	public string $icon_title       = '';
	public string $icon_description = '';

}
