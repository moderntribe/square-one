<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\icon_grid\src;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Project\Blocks\Contracts\Block_Model;
use Tribe\Project\Templates\Models\Collections\Icon_Collection;

class Icon_Grid_Model extends Block_Model {

	public string $title       = '';
	public string $description = '';
	public string $leadin      = '';
	public string $layout      = Icon_Grid::LAYOUT_INLINE;
	public Cta $cta;
	public Icon_Collection $icons;

}
