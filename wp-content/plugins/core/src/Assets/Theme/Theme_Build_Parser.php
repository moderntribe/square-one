<?php
declare( strict_types=1 );

namespace Tribe\Project\Assets\Theme;

use Tribe\Project\Assets\Build_Parser;

class Theme_Build_Parser extends Build_Parser {
	protected $css = 'assets/css/dist/theme/assets.php';
	protected $js  = 'assets/js/dist/theme/assets.php';
}
