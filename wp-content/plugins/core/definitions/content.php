<?php
declare( strict_types=1 );

use Psr\Container\ContainerInterface;
use Tribe\Project\Content\Contact_Page;

return [
	'content.required_pages' => [
		// DI\get( Contact_Page::class ),
	],
	Contact_Page::class      => function ( ContainerInterface $container ) {
		return new Contact_Page( $container->get( \Tribe\Project\Object_Meta\Example::class )->get_group_config()['key'] );
	},
];
