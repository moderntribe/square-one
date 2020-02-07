<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Controllers;

return [
	Controllers\Single::class => DI\autowire()->constructor( 'single.twig' ),
	Controllers\Header::class => DI\autowire()->constructor( 'header.twig' ),

	Controllers\Content\Header\Default_Header::class => DI\autowire()->constructor( 'content/header/default.twig' ),
	Controllers\Content\Header\Subheader::class      => DI\autowire()->constructor( 'content/header/sub.twig' ),

	Controllers\Content\Navigation\Header::class => DI\autowire()->constructor( 'content/navigation/header.twig' ),
];

