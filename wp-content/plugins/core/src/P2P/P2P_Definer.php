<?php
declare( strict_types=1 );

namespace Tribe\Project\P2P;

use DI;
use Tribe\Libs\Container\Definer_Interface;
use Tribe\Libs\P2P\Admin_Search_Filtering;
use Tribe\Project\P2P\Relationships\General_Relationship;

class P2P_Definer implements Definer_Interface {
	public function define(): array {
		return [
			\Tribe\Libs\P2P\P2P_Definer::RELATIONSHIPS => DI\add( [
				DI\get( General_Relationship::class ),
			] ),

			\Tribe\Libs\P2P\P2P_Definer::ADMIN_SEARCH_FILTERS => DI\add( [
				DI\create( Admin_Search_Filtering::class )->constructor( DI\get( General_Relationship::class ), 'both' ),
			] ),

			\Tribe\Libs\P2P\P2P_Definer::TITLE_FILTER_RELATIONSHIPS => DI\add( [
				DI\get( General_Relationship::class ),
			] ),
		];
	}
}
