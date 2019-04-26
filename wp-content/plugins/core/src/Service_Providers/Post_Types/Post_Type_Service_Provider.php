<?php


namespace Tribe\Project\Service_Providers\Post_Types;


use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Libs\Post_Type\Post_Object;
use Tribe\Libs\Post_Type\Post_Type_Config;

abstract class Post_Type_Service_Provider extends Service_Provider {

	/**
	 * @var string The name of the class of the post object. Should have a NAME constant.
	 */
	protected $post_type_class;

	/**
	 * @var string The name of the post type configuration class
	 */
	protected $config_class;

	/**
	 * @var string The id of the post type
	 */
	protected $post_type = '';

	public function __construct() {
		if ( ! is_subclass_of( $this->post_type_class, Post_Object::class ) ) {
			throw new \LogicException( 'Must have a valid post type class reference' );
		}

		if ( empty( $this->post_type_class::NAME ) ) {
			throw new \LogicException( 'Post type class requires a NAME constant' );
		}
		$this->post_type = $this->post_type_class::NAME;
	}

	public function register( Container $container ) {
		$container[ 'post_type.' . $this->post_type ] = $container->factory( function ( $container ) {
			return new $this->post_type_class;
		} );

		$this->register_config( $container );
	}

	protected function register_config( Container $container ) {
		if ( empty( $this->config_class ) ) {
			return;
		}

		if ( ! is_subclass_of( $this->config_class, Post_Type_Config::class ) ) {
			throw new \LogicException( 'If you provide a config class, it must be an instance of Post_Type_Config' );
		}

		$container[ 'post_type.' . $this->post_type . '.config' ] = function ( $container ) {
			return new $this->config_class( $this->post_type );
		};

		add_action( 'init', function () use ( $container ) {
			$container[ 'post_type.' . $this->post_type . '.config' ]->register();
		}, 0, 0 );
	}
}