<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

use Tribe\Libs\Container\Definer_Interface;
use Tribe\Project\Templates\Controllers\Social_Links;

class Templates_Definer implements Definer_Interface {
	public const SOCIAL_SHARING_NETWORKS = 'templates.social_sharing_networks';

	public function define(): array {
		return [
			/**
			 * The social networks that will be used for the
			 * share links
			 */
			self::SOCIAL_SHARING_NETWORKS => [
				Social_Links::FACEBOOK,
				Social_Links::TWITTER,
				Social_Links::LINKEDIN,
				Social_Links::EMAIL,
			],
		];
	}
}
