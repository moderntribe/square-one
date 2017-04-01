<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Libs\Nav\Menu_Location;
use Tribe\Project\Service_Loader;
use Tribe\Project\Theme\Nav;
use Tribe\Project\Theme_Customizer;

abstract class Tribe_Service_Provider implements ServiceProviderInterface {
	/**
	 * @var array The nav menus to register.
	 *            [ 'menu_id' => 'Menu Label' ]
	 */
	protected $nav_menus = [ ];
	/**
	 * @var array P2P relationships to register
	 *            Keys should be the name of a Relationship subclass in
	 *            the namespace \Tribe\Project\P2P\Relationships
	 *            Values should be associative arrays, with keys "from"
	 *            and "to", each containing an array of Post Type classes
	 */
	protected $p2p_relationships = [ ];
	/**
	 * @var array Class names of panel configs to register.
	 *            Classes should be in the namespace
	 *            \Tribe\Project\Panels\Types
	 */
	protected $panels = [ ];
	/**
	 * @var array Class names of post types to register
	 *            Classes should be in the namespace
	 *            \Tribe\Project\Post_Types and should have
	 *            a configuration class in the namespace
	 *            \Tribe\Project\Post_Types\Config (unless
	 *            already registered by a 3rd-party plugin).
	 */
	protected $post_types = [ ];
	/**
	 * @var array Keys are class names of taxonomies to register.
	 *            Classes should be in the namespace
	 *            \Tribe\Project\Taxonomies and should have
	 *            a configuration class in the namespace
	 *            \Tribe\Project\Taxonomies\Config (unless
	 *            already registered by a 3rd-party plugin).
	 *
	 *            Values should be an array of Post Type classes
	 *            that will be registered to use the Taxonomy.
	 */
	protected $taxonomies          = [ ];

	/** @var Service_Loader */
	protected $service_loader;

	public function register( Container $container ) {
		$this->taxonomies( $container );
		$this->p2p( $container );
		$this->nav( $container );
		$this->panels( $container );
	}

	protected function taxonomies( Container $container ) {
		foreach ( $this->taxonomies as $type => $post_types ) {

			$container[ 'taxonomy.' . $type ] = function ( $container ) use ( $type ) {
				$taxonomy_class_name = '\\Tribe\\Project\\Taxonomies\\' . $type;
				return new $taxonomy_class_name;
			};

			$container[ 'taxonomy.' . $type . '.config' ] = function ( $container ) use ( $type, $post_types ) {
				$config_class_name = '\\Tribe\\Project\\Taxonomies\\Config\\' . $type;
				$taxonomy_object = $container[ 'taxonomy.' . $type ];
				$taxonomy = $taxonomy_object::NAME;
				$post_types = $this->map_post_type_classes_to_ids( $post_types, $container );
				if ( ! class_exists( $config_class_name ) ) {
					$config_class_name = '\\Tribe\\Project\\Taxonomies\\Config\\External_Taxonomy_Config';
				}
				return new $config_class_name( $taxonomy, $post_types );
			};

			$container[ 'service_loader' ]->enqueue( 'taxonomy.' . $type . '.config', 'register' );
		}
	}

	protected function p2p( Container $container ) {
		foreach ( $this->p2p_relationships as $relationship => $sides ) {
			$container[ 'p2p.' . $relationship ] = function ( $container ) use ( $relationship, $sides ) {
				$relationship_class_name = '\\Tribe\\Project\\P2P\\Relationships\\' . $relationship;

				$from = $this->post_type_is_user( $sides[ 'from' ] ) ? 'user' : $this->map_post_type_classes_to_ids( $sides[ 'from' ], $container );
				$to   = $this->post_type_is_user( $sides[ 'to' ] ) ? 'user' : $this->map_post_type_classes_to_ids( $sides[ 'to' ], $container );
				return new $relationship_class_name( $from, $to );
			};

			$container[ 'service_loader' ]->enqueue( 'p2p.' . $relationship, 'hook' );
		}
	}

	protected function nav( Container $container ) {
		foreach ( $this->nav_menus as $location => $description ) {
			$container[ 'menu.' . $location ] = function ( $container ) use ( $location, $description ) {
				return new Menu_Location( $location, $description );
			};
			$container[ 'service_loader' ]->enqueue( 'menu.' . $location, 'hook' );
		}
	}

	protected function panels( Container $container ) {
		foreach ( $this->panels as $panel ) {
			$container[ 'panels.init' ]->add_panel_config( $panel );
		}
	}

	protected function post_type_is_user( $side ) {
		return ( in_array( 'User', $side ) || in_array( 'user', $side ) );
	}

	protected function map_post_type_classes_to_ids( $post_type_classes, $container ) {
		return $this->map_object_classes_to_ids( $post_type_classes, 'post_type', $container );
	}

	protected function map_taxonomy_classes_to_ids( $taxonomy_classes, $container ) {
		return $this->map_object_classes_to_ids( $taxonomy_classes, 'taxonomy', $container );
	}

	protected function map_p2p_classes_to_ids( $p2p_classes, $container ) {
		return $this->map_object_classes_to_ids( $p2p_classes, 'p2p', $container );
	}

	protected function map_object_classes_to_ids( $classes, $group, $container ) {
		return array_filter( array_map( function ( $type ) use ( $container, $group ) {
			try {
				$object = $container[ $group . '.' . $type ];
				return $object::NAME;
			} catch ( \InvalidArgumentException $e ) {
				return null; // object isn't known
			}
		}, $classes ) );
	}
}
