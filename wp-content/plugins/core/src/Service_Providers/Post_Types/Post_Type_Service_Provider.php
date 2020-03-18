<?php


namespace Tribe\Project\Service_Providers\Post_Types;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Libs\Post_Type\Post_Object;
use Tribe\Libs\Post_Type\Post_Type_Config;

abstract class Post_Type_Service_Provider extends Service_Provider {

	/**
	 * @var Post_Object The class of the post object. Should have a NAME constant.
	 */
	protected $post_type_class;

	/**
	 * @var Post_Type_Config The post type configuration class
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

	public function register( Container $container ) {
		$factory = function ( $container ) {
			return new $this->post_type_class;
		};
		$container[ 'post_type.' . $this->post_type ] = $factory;
		$container->factory( $factory );
		$this->register_config( $container );
	}

	protected function register_config( Container $container ) {
		if ( isset( $this->config_class ) ) {
			$container[ 'post_type.' . $this->post_type . '.config' ] = function ( $container ) {
				return new $this->config_class( $this->post_type );
			};

			add_action( 'init', function () use ( $container ) {
				$container[ 'post_type.' . $this->post_type . '.config' ]->register();
			}, 0, 0 );
		}
	}
}
