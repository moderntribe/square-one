<?php
declare( strict_types=1 );

namespace Tribe\Project\Taxonomies;

use Tribe\Libs\Container\Abstract_Subscriber;

abstract class Taxonomy_Subscriber extends Abstract_Subscriber {
	/**
	 * @var string The taxonomy configuration class. Should extend Taxonomy_Config
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
