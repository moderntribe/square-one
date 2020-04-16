<?php
declare( strict_types=1 );

namespace Tribe\Project\Integrations\Yoast_SEO;

use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Theme\Config\Image_Sizes;

class Yoast_SEO_Definer implements Definer_Interface {
	public function define(): array {
		return [
			Open_Graph::class => \DI\create()
				->constructor( Image_Sizes::SOCIAL_SHARE ),
		];
	}

}
