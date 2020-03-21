<?php
declare( strict_types=1 );

namespace Tribe\Project\Container;

use Psr\Container\ContainerInterface;

interface Subscriber_Interface {
	/**
	 * Register action/filter listeners too hook into WordPress
	 *
	 * @param ContainerInterface $container The project's global DI container
	 *
	 * @return void
	 */
	public function register( ContainerInterface $container ): void;
}
