<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Controllers;

return [
	Controllers\Single::class => DI\autowire()->constructor( 'single.twig' ),
	Controllers\Header::class => DI\autowire()->constructor( 'header.twig' ),
	Controllers\Footer::class => DI\autowire()->constructor( 'footer.twig' ),

	Controllers\Content\Header\Default_Header::class => DI\autowire()->constructor( 'content/header/default.twig' ),
	Controllers\Content\Header\Subheader::class      => DI\autowire()->constructor( 'content/header/sub.twig' ),
	Controllers\Content\Navigation\Header::class     => DI\autowire()->constructor( 'content/navigation/header.twig' ),
	Controllers\Content\Single\Post::class           => DI\autowire()->constructor( 'content/single/post.twig' ),
	Controllers\Content\Footer\Default_Footer::class => DI\autowire()->constructor( 'content/footer/default.twig' ),
	Controllers\Content\Navigation\Footer::class     => DI\autowire()->constructor( 'content/navigation/footer.twig' ),
];

