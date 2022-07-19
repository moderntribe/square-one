<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use DI;
use Psr\SimpleCache\CacheInterface;
use Sabre\Cache\Memory;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Taxonomies\Category\Category;

class Post_Loop_Definer implements Definer_Interface {

	public const ALLOWED_TAXONOMIES = 'post_loop.allowed_taxonomies';

	public function define(): array {
		return [
			CacheInterface::class    => DI\get( Memory::class ),

			// When using manual posts, taxonomies that can have their terms replaced.
			self::ALLOWED_TAXONOMIES => DI\add( [
				Category::NAME,
			] ),

			Term_Manager::class      => DI\autowire()
				->constructorParameter(
					'allowed_taxonomies',
					DI\get( self::ALLOWED_TAXONOMIES )
				),
		];
	}

}
