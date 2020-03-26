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

	protected static $_instance;

	/** @var \Pimple\Container */
	protected $container = null;

	/** @var ContainerInterface */
	protected $template_container;

	/**
	 * @param \Pimple\Container $container
	 */
	public function __construct( \Pimple\Container $container ) {
		$this->container = $container;
	}

	public function init() {
		$this->init_template_container();
	}

	private function init_template_container(): void {
		/** @var string[] $definers Names of classes implementing Definer_Interface */
		$definers = [
			Assets_Definer::class,
			Content_Definer::class,
			Nav_Menus_Definer::class,
			Object_Meta_Definer::class,
			P2P_Definer::class,
			Panels_Definer::class,
			Theme_Definer::class,
			Twig_Definer::class,
		];

		/** @var string[] $subscribers Names of classes implementing Subscriber_Interface */
		$subscribers = [
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

		if ( defined( 'WHOOPS_ENABLE' ) && WHOOPS_ENABLE && class_exists( '\Whoops\Run' ) ) {
			$definers[]    = Whoops_Definer::class;
			$subscribers[] = Whoops_Subscriber::class;
		}

		if ( class_exists( '\Tribe\Libs\Queues\Queues_Definer' ) ) {
			$definers[]    = '\Tribe\Libs\Queues\Queues_Definer';
			$subscribers[] = '\Tribe\Libs\Queues\Queues_Subscriber';
		}

		if ( class_exists( '\Tribe\Libs\Queues_Mysql\Mysql_Backend_Definer' ) ) {
			$definers[]    = '\Tribe\Libs\Queues_Mysql\Mysql_Backend_Definer';
			$subscribers[] = '\Tribe\Libs\Queues_Mysql\Mysql_Backend_Subscriber';
		}

		if ( class_exists( '\Tribe\Libs\Blog_Copier\Blog_Copier_Definer' ) ) {
			$definers[]    = '\Tribe\Libs\Blog_Copier\Blog_Copier_Definer';
			$subscribers[] = '\Tribe\Libs\Blog_Copier\Blog_Copier_Subscriber';
		}

		if ( class_exists( '\Tribe\Libs\Generators\Generator_Definer' ) ) {
			$definers[]    = '\Tribe\Libs\Generators\Generator_Definer';
			$subscribers[] = '\Tribe\Libs\Generators\Generator_Subscriber';
		}

		/**
		 * Filter the list of definers that power the plugin
		 *
		 * @param string[] $definers The class names of definers that will be instantiated
		 */
		$definers = apply_filters( 'tribe/project/definers', $definers );

		/**
		 * Filter the list subscribers that power the plugin
		 *
		 * @param string[] $subscribers The class names of subscribers that will be instantiated
		 */
		$subscribers = apply_filters( 'tribe/project/subscribers', $subscribers );

		$builder = new \DI\ContainerBuilder();
		$builder->addDefinitions( [ 'plugin.file' => dirname( __DIR__ ) . '/core.php' ] );
		$builder->addDefinitions( ... array_map( function ( $classname ) {
			return ( new $classname() )->define();
		}, $definers ) );

		$this->template_container = $builder->build();

		foreach ( $subscribers as $subscriber_class ) {
			$this->template_container->get( $subscriber_class )->register( $this->template_container );
		}
	}

	public function container() {
		return $this->container;
	}

	public function template_container(): ContainerInterface {
		return $this->template_container;
	}

	/**
	 * @param null|\ArrayAccess $container
	 *
	 * @return Core
	 * @throws \Exception
	 */
	public static function instance( $container = null ) {
		if ( ! isset( self::$_instance ) ) {
			if ( empty( $container ) ) {
				throw new \Exception( 'You need to provide a Pimple container' );
			}

			$className       = __CLASS__;
			self::$_instance = new $className( $container );
		}

		return self::$_instance;
	}

}
