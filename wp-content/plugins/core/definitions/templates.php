<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Controllers;

return [
	Controllers\Index::class  => DI\autowire()->constructor( 'index.twig' ),
	Controllers\Page::class   => DI\autowire()->constructor( 'page.twig' ),
	Controllers\Search::class => DI\autowire()->constructor( 'search.twig' ),
	Controllers\Single::class => DI\autowire()->constructor( 'single.twig' ),

	Controllers\Header::class  => DI\autowire()->constructor( 'header.twig' ),
	Controllers\Footer::class  => DI\autowire()->constructor( 'footer.twig' ),
	Controllers\Sidebar::class => DI\autowire()->constructor( 'sidebar.twig' ),

	Controllers\Content\Header\Default_Header::class => DI\autowire()->constructor( 'content/header/default.twig' ),
	Controllers\Content\Header\Subheader::class      => DI\autowire()->constructor( 'content/header/sub.twig' ),
	Controllers\Content\Navigation\Header::class     => DI\autowire()->constructor( 'content/navigation/header.twig' ),
	Controllers\Content\Single\Post::class           => DI\autowire()->constructor( 'content/single/post.twig' ),
	Controllers\Content\Footer\Default_Footer::class => DI\autowire()->constructor( 'content/footer/default.twig' ),
	Controllers\Content\Navigation\Footer::class     => DI\autowire()->constructor( 'content/navigation/footer.twig' ),
	Controllers\Content\Loop\Item::class             => DI\autowire()->constructor( 'content/loop/item.twig' ),
	Controllers\Content\Search\Item::class           => DI\autowire()->constructor( 'content/search/item.twig' ),
];

