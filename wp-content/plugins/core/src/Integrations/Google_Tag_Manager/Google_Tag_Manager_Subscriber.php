<?php declare(strict_types=1);

namespace Tribe\Project\Integrations\Google_Tag_Manager;

use Tribe\Libs\Container\Abstract_Subscriber;

class Google_Tag_Manager_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_action( 'wp_head', function (): void {
			$this->container->get( GTM_Scripts::class )->inject_google_tag_manager_head_tag();
		} );
		add_action( 'wp_body_open', function (): void {
			$this->container->get( GTM_Scripts::class )->inject_google_tag_manager_body_tag();
		} );
	}

}
