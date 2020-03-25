<?php
declare( strict_types=1 );

namespace Tribe\Project\Post_Types;

use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Subscriber_Interface;

abstract class Post_Type_Subscriber implements Subscriber_Interface {
	/**
	 * @var string The post type configuration class. Should extend Post_Type_Config
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
