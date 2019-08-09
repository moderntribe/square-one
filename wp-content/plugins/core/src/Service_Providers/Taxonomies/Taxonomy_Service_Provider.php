<?php


namespace Tribe\Project\Service_Providers\Taxonomies;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Libs\Taxonomy\Taxonomy_Config;

abstract class Taxonomy_Service_Provider extends Service_Provider {

	/**
	 * @var Object The class of the taxonomy term object. Should have a NAME constant.
	 */
	protected $taxonomy_class;

	/**
	 * @var Taxonomy_Config The taxonomy configuration class
	 */
	protected $config_class;

	/**
	 * @var string[] The post types to which this taxonomy will be assigned
	 */
	protected $post_types = [];

	/**
	 * @var string The id of the taxonomy
	 */
	protected $taxonomy = '';

	public function __construct() {
		if ( ! isset( $this->taxonomy_class ) ) {
			throw new \LogicException( 'Must have a valid taxonomy class reference' );
		}
		if ( ! defined( $this->taxonomy_class . '::NAME' ) ) {
			throw new \LogicException( 'Taxonomy class requires a NAME constant' );
		}
		$this->taxonomy = $this->taxonomy_class::NAME;
	}

	public function register( Container $container ) {
		$factory                                    = function ( $container ) {
			return new $this->taxonomy_class();
		};
		$container[ 'taxonomy.' . $this->taxonomy ] = $factory;
		$container->factory( $factory );

		$this->register_config( $container );
	}

	protected function register_config( Container $container ) {
		if ( isset( $this->config_class ) ) {
			$container[ 'taxonomy.' . $this->taxonomy . '.config' ] = function ( $container ) {
				return new $this->config_class( $this->taxonomy, $this->post_types );
			};
		} else {
			// ensures that the taxonomy is associated with appropriate post types, even
			// if initial registration is from an external plugin
			$container[ 'taxonomy.' . $this->taxonomy . '.config' ] = function ( $container ) {
				return new class( $this->taxonomy, $this->post_types ) extends Taxonomy_Config {
					public function get_args() {
						return []; // already registered
					}
					public function get_labels() {
						return []; // already registered
					}
				};
			};
		}

		add_action( 'init', function() use ( $container ) {
			$container[ 'taxonomy.' . $this->taxonomy . '.config' ]->register();
		}, 0, 0 );
	}
}
