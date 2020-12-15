<?php
declare( strict_types=1 );

namespace Tribe\Project\Settings;

use DI;
use Tribe\Libs\Container\Definer_Interface;

class Settings_Definer implements Definer_Interface {
	public function define(): array {
		return [

			// add the settings screens to the global array
			\Tribe\Libs\Settings\Settings_Definer::PAGES => DI\add( [
				DI\get( General::class ),
			] ),

		];
	}
}
