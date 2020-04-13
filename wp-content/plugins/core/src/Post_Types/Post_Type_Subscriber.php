<?php
declare( strict_types=1 );

namespace Tribe\Project\Post_Types;

use Tribe\Libs\Container\Abstract_Subscriber;

abstract class Post_Type_Subscriber extends Abstract_Subscriber {
	/**
	 * @var string The post type configuration class. Should extend Post_Type_Config
	 */
	protected $config_class;

	public function register(): void {
		if ( isset( $this->config_class ) ) {
			add_action( 'init', function () {
				$this->container->get( $this->config_class )->register();
			}, 0, 0 );
		}
	}
}
