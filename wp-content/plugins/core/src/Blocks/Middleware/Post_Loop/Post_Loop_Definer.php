<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use DI;
use Psr\SimpleCache\CacheInterface;
use Sabre\Cache\Memory;
use Tribe\Libs\Container\Definer_Interface;

class Post_Loop_Definer implements Definer_Interface {

	public function define(): array {
		return [
			CacheInterface::class => DI\get( Memory::class ),
		];
	}

}
