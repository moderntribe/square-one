<?php
declare( strict_types=1 );

namespace Tribe\Project\Taxonomies;

use Psr\Container\ContainerInterface;
use Tribe\Project\Container\Subscriber_Interface;

abstract class Taxonomy_Subscriber implements Subscriber_Interface {
	/**
	 * @var string The taxonomy configuration class. Should extend Taxonomy_Config
	 */
	protected $config_class;

	public function register( ContainerInterface $container ): void {
		if ( isset( $this->config_class ) ) {
			add_action( 'init', function () use ( $container ) {
				$container->get( $this->config_class )->register();
			}, 0, 0 );
		}
	}
}
