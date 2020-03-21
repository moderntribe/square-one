<?php
declare( strict_types=1 );

use Tribe\Libs\Assets\Asset_Loader;

return [
	Asset_Loader::class => DI\create()
		->constructor( DI\get( 'plugin.file' ) ),
];
