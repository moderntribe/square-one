<?php
declare( strict_types=1 );

namespace Tribe\Project\Content;

use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Definer_Interface;

class Content_Definer implements Definer_Interface {
	public const REQUIRED_PAGES = 'content.required_pages';

	public function define(): array {
		return [
			self::REQUIRED_PAGES => [
				// DI\get( Contact_Page::class ),
			],
			Contact_Page::class      => function ( ContainerInterface $container ) {
				return new Contact_Page( $container->get( \Tribe\Project\Object_Meta\Example::class )->get_group_config()['key'] );
			},
		];
	}
}
