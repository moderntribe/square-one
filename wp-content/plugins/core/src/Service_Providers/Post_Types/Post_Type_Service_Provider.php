<?php


namespace Tribe\Project\Service_Providers\Post_Types;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

abstract class Post_Type_Service_Provider implements ServiceProviderInterface {
	const NAME = '';
	protected $post_type_class;
	protected $config_class;
	protected $post_type;

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
		$container[ 'post_type.' . $this->post_type ] = function ( $container )  {
			return new $this->post_type_class;
		};
		$this->register_config( $container );
	}

	protected function register_config( Container $container ) {
		if ( isset( $this->config_class ) ) {
			$container[ 'post_type.' . $this->post_type . '.config' ] = function ( $container ) {
				return new $this->config_class( $this->post_type );
			};

			add_action( 'init', function() use ( $container ) {
				$container[ 'post_type.' . $this->post_type . '.config' ]->register();
			}, 0, 0 );
		}
	}
}