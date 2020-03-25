<?php
declare( strict_types=1 );

namespace Tribe\Project\P2P;

use DI;
use Tribe\Libs\Assets\Asset_Loader;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\P2P\Relationships\General_Relationship;
use Tribe\Project\P2P\Relationships\Sample_To_Page;

class P2P_Definer implements Definer_Interface {
	public const RELATIONSHIPS = 'p2p.relationships';
	public const ADMIN_FILTERS = 'p2p.admin_search_filters';

	public function define(): array {
		return [
			self::RELATIONSHIPS  => [
				General_Relationship::class,
				Sample_To_Page::class,
			],
			self::ADMIN_FILTERS  => [
				DI\create( Admin_Search_Filtering::class )
					->constructor( DI\get( General_Relationship::class ), 'both', DI\get( Asset_Loader::class ) ),
			],
			Titles_Filter::class => DI\create()
				->constructor( [
					General_Relationship::NAME,
				] ),
		];
	}
}
