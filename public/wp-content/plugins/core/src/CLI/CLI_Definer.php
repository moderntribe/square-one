<?php declare(strict_types=1);

namespace Tribe\Project\CLI;

use DI;
use Tribe\Libs\CLI\CLI_Definer as Libs_Definer;
use Tribe\Libs\Container\Definer_Interface;

class CLI_Definer implements Definer_Interface {

	/**
	 * Register an array of custom command definitions.
	 *
	 * @example DI\get( Command_Name::class ),
	 */
	public function define(): array {
		return [
			Libs_Definer::COMMANDS => DI\add( [] ),
		];
	}

}
