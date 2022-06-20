<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Models;

use Tribe\Libs\Field_Models\Field_Model;
use Tribe\Libs\Field_Models\Models\Image;
use Tribe\Libs\Field_Models\Models\Link;

class Logo extends Field_Model {

	public Image $image;
	public Link $link;

}
