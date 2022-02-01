<?php declare(strict_types=1);

namespace Tribe\Project\Assets\Admin;

use Tribe\Project\Assets\Build_Parser;

class Admin_Build_Parser extends Build_Parser {

	protected string $css = 'assets/css/dist/admin/assets.php';
	protected string $js  = 'assets/js/dist/admin/assets.php';

}
