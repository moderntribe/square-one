<?php
declare( strict_types=1 );

use Tribe\Libs\Assets\Asset_Loader;
use Tribe\Project\P2P\Admin_Search_Filtering;
use Tribe\Project\P2P\Relationships\General_Relationship;
use Tribe\Project\P2P\Relationships\Sample_To_Page;
use Tribe\Project\P2P\Titles_Filter;

return [
	'p2p.relationships'        => [
		General_Relationship::class,
		Sample_To_Page::class,
	],
	'p2p.admin_search_filters' => [
		DI\create( Admin_Search_Filtering::class )
			->constructor( DI\get( General_Relationship::class ), 'both', DI\get( Asset_Loader::class ) ),
	],
	Titles_Filter::class       => DI\create()
		->constructor( [
			General_Relationship::NAME,
		] ),
];
