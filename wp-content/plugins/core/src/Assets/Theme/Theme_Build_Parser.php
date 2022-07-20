<?php declare(strict_types=1);

namespace Tribe\Project\Assets\Theme;

use Tribe\Project\Assets\Build_Parser;

class Theme_Build_Parser extends Build_Parser {

	protected string $css = 'assets/css/dist/theme/assets.php';
	protected string $js  = 'assets/js/dist/theme/assets.php';

	public function get_build_parser_style_handles(): array {
		return $this->get_style_handles();
	}

}
