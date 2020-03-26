<?php

namespace Tribe\Project;

use Psr\Container\ContainerInterface;
use Tribe\Libs\Assets\Assets_Definer;
use Tribe\Project\Admin\Admin_Subscriber;
use Tribe\Project\Cache\Cache_Subscriber;
use Tribe\Project\CLI\CLI_Subscriber;
use Tribe\Project\Content\Content_Definer;
use Tribe\Project\Content\Content_Subscriber;
use Tribe\Project\Development\Whoops_Definer;
use Tribe\Project\Development\Whoops_Subscriber;
use Tribe\Project\Nav_Menus\Nav_Menus_Definer;
use Tribe\Project\Nav_Menus\Nav_Menus_Subscriber;
use Tribe\Project\Object_Meta\Object_Meta_Definer;
use Tribe\Project\Object_Meta\Object_Meta_Subscriber;
use Tribe\Project\P2P\P2P_Definer;
use Tribe\Project\P2P\P2P_Subscriber;
use Tribe\Project\Panels\Panels_Definer;
use Tribe\Project\Panels\Panels_Subscriber;
use Tribe\Project\Post_Types;
use Tribe\Project\Settings\Settings_Subscriber;
use Tribe\Project\Shortcodes\Shortcodes_Subscriber;
use Tribe\Project\Taxonomies;
use Tribe\Project\Templates\Templates_Subscriber;
use Tribe\Project\Theme\Theme_Definer;
use Tribe\Project\Theme\Theme_Subscriber;
use Tribe\Project\Twig\Twig_Definer;

class Core {
	public const PLUGIN_FILE = 'plugin.file';

	/**
	 * @var self
	 */
	private static $_instance;

	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @var string[] Names of classes implementing Definer_Interface
	 */
	private $definers = [
		Assets_Definer::class,
		Content_Definer::class,
		Nav_Menus_Definer::class,
		Object_Meta_Definer::class,
		P2P_Definer::class,
		Panels_Definer::class,
		Theme_Definer::class,
		Twig_Definer::class,
	];

	/**
	 * @var string[] Names of classes implementing Subscriber_Interface
	 */
	private $subscribers = [
		Admin_Subscriber::class,
		\Tribe\Libs\Cache\Cache_Subscriber::class,
		Cache_Subscriber::class,
		CLI_Subscriber::class,
		Content_Subscriber::class,
		Nav_Menus_Subscriber::class,
		Object_Meta_Subscriber::class,
		Panels_Subscriber::class,
		P2P_Subscriber::class,
		Settings_Subscriber::class,
		Shortcodes_Subscriber::class,
		Theme_Subscriber::class,
		Templates_Subscriber::class,

		// our post types
		Post_Types\Sample\Subscriber::class,

		// our taxonomies
		Taxonomies\Example\Subscriber::class,
	];


	public function init( string $plugin_path ) {
		$this->init_container( $plugin_path );
	}

	private function init_container( string $plugin_path ): void {
		$this->extend_definers();
		$this->extend_subscribers();

		/**
		 * Filter the list of definers that power the plugin
		 *
		 * @param string[] $definers The class names of definers that will be instantiated
		 */
		$this->definers = apply_filters( 'tribe/project/definers', $this->definers );

		/**
		 * Filter the list subscribers that power the plugin
		 *
		 * @param string[] $subscribers The class names of subscribers that will be instantiated
		 */
		$this->subscribers = apply_filters( 'tribe/project/subscribers', $this->subscribers );

		$builder = new \DI\ContainerBuilder();
		$builder->addDefinitions( [ self::PLUGIN_FILE => $plugin_path ] );
		$builder->addDefinitions( ... array_map( function ( $classname ) {
			return ( new $classname() )->define();
		}, $this->definers ) );

		$this->container = $builder->build();

		foreach ( $this->subscribers as $subscriber_class ) {
			$this->container->get( $subscriber_class )->register( $this->container );
		}
	}

	private function extend_definers(): void {
		$optional_definers = array_filter( [
			'\Tribe\Libs\Queues\Queues_Definer',
			'\Tribe\Libs\Queues_Mysql\Mysql_Backend_Definer',
			'\Tribe\Libs\Blog_Copier\Blog_Copier_Definer',
			'\Tribe\Libs\Generators\Generator_Definer',
		], 'class_exists' );

		$this->definers = array_merge( $this->definers, $optional_definers );

		if ( defined( 'WHOOPS_ENABLE' ) && WHOOPS_ENABLE && class_exists( '\Whoops\Run' ) ) {
			$this->definers[] = Whoops_Definer::class;
		}
	}

	private function extend_subscribers(): void {
		$optional_subscribers = array_filter( [
			'\Tribe\Libs\Queues\Queues_Subscriber',
			'\Tribe\Libs\Queues_Mysql\Mysql_Backend_Subscriber',
			'\Tribe\Libs\Blog_Copier\Blog_Copier_Subscriber',
			'\Tribe\Libs\Generators\Generator_Subscriber',
		], 'class_exists' );

		$this->subscribers = array_merge( $this->subscribers, $optional_subscribers );

		if ( defined( 'WHOOPS_ENABLE' ) && WHOOPS_ENABLE && class_exists( '\Whoops\Run' ) ) {
			$this->subscribers[] = Whoops_Subscriber::class;
		}
	}

	public function container(): ContainerInterface {
		return $this->container;
	}

	/**
	 * @return self
	 */
	public static function instance() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

}
