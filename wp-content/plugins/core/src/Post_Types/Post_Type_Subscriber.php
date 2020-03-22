<?php
declare( strict_types=1 );

namespace Tribe\Project\Post_Types;

use Psr\Container\ContainerInterface;
use Tribe\Project\Container\Subscriber_Interface;

abstract class Post_Type_Subscriber implements Subscriber_Interface {
	/**
	 * @var string The class of the post object. Should extend Post_Object and have a NAME constant.
	 */
	protected $post_type_class;

	/**
	 * @var string The post type configuration class. Should extend Post_Type_Config
	 */
	protected $config_class;

	/**
	 * @var string The id of the post type
	 */
	protected $post_type = '';

	public function __construct() {
		if ( ! isset( $this->post_type_class ) ) {
			throw new \LogicException( 'Must have a valid post type class reference' );
		}
		if ( ! defined( $this->post_type_class . '::NAME' ) ) {
			throw new \LogicException( 'Post type class requires a NAME constant' );
		}
		$this->post_type = $this->post_type_class::NAME;
	}

	public function register( ContainerInterface $container ): void {
		if ( isset( $this->config_class ) ) {
			add_action( 'init', function() use ( $container ) {
				$container->get( $this->config_class )->register();
			}, 0, 0 );
		}
	}
}
